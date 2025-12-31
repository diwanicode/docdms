<?php

namespace App\Repositories\Business;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;  
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\Business\BusinessClientResource;
use App\Http\Resources\Business\BusinessClientDropdownResource;
use App\Models\User;
use App\Models\Business;
use App\Models\BusinessEmployee;
use App\Models\BusinessClient;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BusinessClientRepository
{
    public function findClientByUuid(Business $business, string $uuid)
    {
        return $business->businessClients()->where('client_key',$uuid)->first();
    }
    public function findClientById(Business $business, int $id)
    {
        return $business->businessClients()->where('id',$id)->first();
    }
    public function findClientsByBusinessDropdown(Business $business, bool $onlyColumns = false)
    {
        $query = $business->businessClients() 
                            ->where('is_active',true)
                            ->get();  
        return BusinessClientDropdownResource::collection($query)->toArray(request()); 
    }
    public function findClientsByBusiness(Business $business, bool $onlyColumns = false)
    {
        $query =  $business->businessClients()
                            ->orderBy('is_active','desc')
                            ->with(['businessType','country','businessEmployee']);
        if ($onlyColumns) {
            return BusinessClientResource::columns($business);
        }
        $clients =$query->get();
        return BusinessClientResource::collection($clients)->toArray(request());  
    }
    public function storeClient(Business $business,BusinessEmployee $businessEmployee, User $user, array $data)
    {
        try { 
            return  $business->businessClients()->create([
                        'name'         => $data['name'],  
                        'contact'      => $data['contact'],                        
                        'user_id'      => $user->id, 
                        'vat_number'   => $data['vat_number'],
                        'email'        => $data['email'],                        
                        'number'       => $data['number'] ?? null,
                        'business_type_id' =>$data['business_type_id'],
                        'business_employee_id'=>$businessEmployee->id,
                        'country_id'   => 1,
                        'city'         => $data['city'],
                        'address'      => $data['address'],
                        'start_date'   => Carbon::parse($data['start_date'])->format('Y-m-d'),
                        'end_date'     => $data['end_date'] ?? null,
                        'is_active'    => $data['is_active'],
                        'note'    => $data['note']  
                    ]);
        } catch (\Exception $e) {
            Log::error("Client store failed in repository", [
                'data' => $data,
                'error' => $e->getMessage()
            ]);

            throw new \RuntimeException(
                'Could not store client at this time. Error: ' . $e->getMessage(),
                0,
                $e // Preserve the previous exception
            );
        }
    }
    public function updateClient( BusinessClient $businessClient, array $data)
    {
        try { 
            Log::info($data);
            return  $businessClient->update([
                        'name'         => $data['name'],  
                        'contact'      => $data['contact'],  
                        'vat_number'   => $data['vat_number'],
                        'email'        => $data['email'],                        
                        'number'       => $data['number'] ?? null,
                        'business_type_id' =>$data['business_type_id'],  
                        'city'         => $data['city'],
                        'address'      => $data['address'],
                        'start_date'   => Carbon::parse($data['start_date'])->format('Y-m-d'),
                        'end_date'     => $data['end_date'] ?? null,
                        'is_active'    => $data['is_active'],
                        'note'    => $data['note']
                    ]);
        } catch (\Exception $e) {
            Log::error("Client update failed in repository", [
                'data' => $data,
                'error' => $e->getMessage()
            ]);

            throw new \RuntimeException(
                'Could not update client at this time. Error: ' . $e->getMessage(),
                0,
                $e // Preserve the previous exception
            );
        }
    }
  
}
