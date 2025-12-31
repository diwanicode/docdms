<?php

namespace App\Repositories\Business;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\Business\BusinessEmployeeResource;
use App\Models\User;
use App\Models\Business;
use App\Models\BusinessEmployee;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BusinessEmployeeRepository
{
    public function findEmployeeByUuid(Business $business, string $uuid)
    {
        return $business->businessEmployees()->where('employee_key',$uuid)->first();
    }
    public function findEmployeesByUser(Business $business, User $user)
    {
        return $business->businessEmployees()->where('user_id',$user->id)->first();
    }
    public function findEmployeeIdsByUuids(Business $business, array $uuids)
    {
        return $business->businessEmployees()
                ->whereIn('employee_key',$uuids) ->pluck('id')
                ->toArray();
    }
    public function findEmployeesByBusinessDropdown(Business $business, bool $onlyColumns = false)
    {
        return $business->businessEmployees()
                            ->select('employee_key as id','name')
                            ->where('is_active',true)
                            ->get();  
    }
    public function findEmployeesByBusiness(Business $business, bool $onlyColumns = false)
    {
        $query =  $business->businessEmployees()->orderBy('is_active','desc')->with('departments');
        if ($onlyColumns) {
            return BusinessEmployeeResource::columns($business);
        }
        $employees =$query->get();
        return BusinessEmployeeResource::collection($employees)->toArray(request());  
    }
    public function findEmployeesWithNewPassword(Business $business)
    {
        $owners = $business->employees()->wherePivot('is_owner', true)->get();
        $data =[];
        foreach ($owners as $owner) {
            $tempPassword = Str::random(10);
            $owner->password = Hash::make($tempPassword);
            $owner->save();
            $data[] = [
                'name' => $owner->name,
                'tempPassword' => $tempPassword,
                'email' =>  $owner->email,
            ];
        }
        return $data;
    }
    /**
     * Determine who is performing an action (user, owner, employee, system).
     */
    public function getActionType(Business $business): string
    {
       
        $user = Auth::user();
        if (!$user) {
            return 'customer';
        }
       
        if ($this->isOwner($business,$user)) {
            return 'owner';
        }
     
        if ($this->isEmployee($business,$user)) {
            return 'employee';
        }
       
        // if ($this->isSystemAdmin($user)) {
        //     return 'admin';
        // }
        return 'customer';
    }
    /**
     * Check if a business is owned by a given user
     *
     * @param  Business  $business 
     * @param User  $user 
     * @return bool
     */
    public function isOwner(Business $business, User $user): bool
    {
        return $business->businessEmployees()
            ->where('user_id', $user->id)
            ->where('is_owner', true)
            ->exists();
    }
      /**
     * Check if a user is employee  of business
     *
     * @param  Business  $business 
     * @param User  $user 
     * @return bool
     */
    public function isEmployee(Business $business, User $user): bool
    {
        return $business->businessEmployees()
            ->where('user_id', $user->id)
            ->where('is_working', true)
            ->exists();
    }
    public function isSystemAdmin(User $user): bool
    {  
        $businessEmployee = $user->businessEmployees()->first();
        if (! $businessEmployee) {
            return false;
        }
        return true;
      //  return $businessEmployee->business->isSysAdmin();
    }
    public function storeEmployee(Business $business, array $data)
    {
        try { 
            return  $business->businessEmployees()->create( [
                        'name'         => $data['name'],                        
                        'user_id'      => $user->id, 
                        'work_email'   => $data['email'],                        
                        'phone_number' => $data['phone_number'] ?? null,
                        'start_date'   => $data['start_date'],
                        'end_date'     => $data['end_date'] ?? null,
                        'is_active'    => $data['is_active'],
                        'is_working'   => $data['is_working'],
                        'is_owner'     => $data['is_owner'],
                    ]);
        } catch (\Exception $e) {
            Log::error("Employee store failed in repository", [
                'data' => $data,
                'error' => $e->getMessage()
            ]);

            throw new \RuntimeException(
                'Could not store employee at this time. Error: ' . $e->getMessage(),
                0,
                $e // Preserve the previous exception
            );
        }
    }
     public function updateEmployee(BusinessEmployee $businessEmployee, array $data)
    {
        try { 
            return  $businessEmployee->update([
                        'name'         => $data['name'],
                        'work_email'   => $data['email'],                        
                        'phone_number' => $data['phone_number'] ?? null,
                        'start_date'   => Carbon::parse($data['start_date'])->format('Y-m-d'),
                        'end_date'     => $data['end_date'] ?? null,
                        'is_active'    => $data['is_active'],
                        'is_working'   => $data['is_working'],
                        'is_owner'     => $data['is_owner'],
                    ]);
        } catch (\Exception $e) {
            Log::error("Employee update failed in repository", [
                'data' => $data,
                'error' => $e->getMessage()
            ]);

            throw new \RuntimeException(
                'Could not update employee at this time. Error: ' . $e->getMessage(),
                0,
                $e // Preserve the previous exception
            );
        }
    }
}
