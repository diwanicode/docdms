<?php

namespace App\Repositories\Business;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\Business\BusinessDepartmentResource;
use App\Models\User;
use App\Models\Business;
use App\Models\BusinessDepartment;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BusinessDepartmentRepository
{
    public function findDepartmentByUuid(Business $business, string $uuid)
    {
        return $business->businessDepartments()->where('key',$uuid)->first();
    }
   
    public function findDepartmentsByBusiness(Business $business, bool $onlyColumns = false)
    {
        if ($onlyColumns) {
            return BusinessDepartmentResource::columns($business);
        }
        
        $query =  $business->businessDepartments()->with(['permissions.countries','employees']);
        
        $departments =$query->get();
        return BusinessDepartmentResource::collection($departments)->toArray(request());  
    }
    public function storeBusinessDepartment(Business $business, array $data): BusinessDepartment
    {
        try { 
            return $business->businessDepartments()->create([
                'name'        => $data['name'],
                'description'        => $data['description']
            ]);
        } catch (\Exception $e) {
            Log::error("Departmnet creation failed in repository", [
                'data' => $data,
                'error' => $e->getMessage()
            ]);

            throw new \RuntimeException(
                'Could not create department at this time. Error: ' . $e->getMessage(),
                0,
                $e // Preserve the previous exception
            );
        }
    }
     public function updateBusinessDepartment( BusinessDepartment $businessDepartment, array $data)
    {
        try { 
            return $businessDepartment->update([
                'name'        => $data['name'],
                'description'        => $data['description']
            ]);
        } catch (\Exception $e) {
            Log::error("Departmnet update failed in repository", [
                'data' => $data,
                'error' => $e->getMessage()
            ]);

            throw new \RuntimeException(
                'Could not update department at this time. Error: ' . $e->getMessage(),
                0,
                $e // Preserve the previous exception
            );
        }
    }
      
}
