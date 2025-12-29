<?php

namespace App\Repositories\Business;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use App\Exceptions\ServiceNotFoundException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;  
use Illuminate\Support\Str;

use App\Http\Resources\Business\BusinessDetailsResource; 
use App\Models\User;
use App\Models\Business;
use App\Models\BusinessType;
use App\Models\SocialMediaPlatform;
use Carbon\Carbon;

class BusinessTypeRepository
{
    public function findBusinessTypeBySlug(string $slug) 
    {
        return Business::select('id','name', 'slug')
                            ->where('slug',  $slug)
                            ->first(); 
    } 
    public function findBusinessTypes(Business $business) 
    {
        return BusinessType::select('id','short as name')
            ->where('country_id', $business->country_id)
            ->get(); 
 
    } 
    public function findSocialMediaPlatforms() 
    {
        return  SocialMediaPlatform::all(); 
    }
    public function createBusiness(Business $business,array $data): Business
    {
        try { 
            return $business->create($data);
        } catch (\Exception $e) {
            Log::error("Business creation failed in repository", [
                'data' => $data,
                'error' => $e->getMessage()
            ]);

            throw new \RuntimeException(
                'Could not create business at this time. Error: ' . $e->getMessage(),
                0,
                $e // Preserve the previous exception
            );
        }
    }
    public function updateBusiness(Business $business, array $data):Business
    {
        try { 
            if (isset($data['logo']) && $data['logo'] instanceof UploadedFile) {
                $data['logo'] = $this->handleLogoUpload($business, $data['logo']);
            } else {
                if ($business->logo && \Storage::disk('public')->exists($business->logo)) {
                    \Storage::disk('public')->delete($business->logo);
                }
                $data['logo']=null; 
            }                        
            $business->update($data); 
            return $business;          
        } catch (\Exception $e) {
            Log::error("Business update failed", [
                'error' => $e->getMessage()
            ]);

            throw new \RuntimeException('Could not update business  at this time.');
        }
    }
    protected function handleLogoUpload(Business $business, UploadedFile $file): string
    { 
        $timestamp = now()->format('YmdHis');
        $random = Str::random(8);
        $hash = md5($business->slug . $timestamp . $random);
        $extension = $file->getClientOriginalExtension();
        $filename = "{$hash}.{$extension}";;
          
        $path = $file->storeAs('business_logos', $filename, 'public');
        
        if ($business->logo && \Storage::disk('public')->exists($business->logo)) {
            \Storage::disk('public')->delete($business->logo);
        }

        return $path;
    }
    
}
