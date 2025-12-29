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
use App\Models\BusinessEmployee;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BusinessEmployeeDepartmentRepository
{
    public function findEmployeeByUuid(Business $business, string $uuid)
    {
        return $business->businessEmployees()->where('employee_key',$uuid)->first();
    }
    public function findEmployeeIdsByUuids(Business $business, array $uuids)
    {
        return $business->businessEmployees()
               ->whereIn('employee_key',$uuids) ->pluck('id')
               ->toArray();
    }
     public function findDepartmentIdsByUuids(Business $business, array $uuids)
    {
        return $business->businessDepartments()
               ->whereIn('key',$uuids) ->pluck('id')
               ->toArray();
    }
   
    public function syncEmployees(Business $business, BusinessDepartment $department, array $employees): void
    {
        $employeeUuids = collect($employees)
            ->pluck('id')
            ->filter()
            ->values()
            ->toArray();

        if (empty($employeeUuids)) {
            $department->employees()->sync([]);
            return;
        }

        // convert UUIDs → internal IDs (business-safe)
        $employeeIds = $this->findEmployeeIdsByUuids($business, $employeeUuids);

        // sync pivot
        $department->employees()->sync($employeeIds);
    }
     public function syncDepartments(Business $business, BusinessEmployee $employee, array $departments): void
    {
        $departmentUuids = collect($departments)
            ->pluck('id')
            ->filter()
            ->values()
            ->toArray();

        if (empty($departmentUuids)) {
            $employee->departments()->sync([]);
            return;
        }

        // convert UUIDs → internal IDs (business-safe)
        $departmentIds = $this->findDepartmentIdsByUuids($business, $departmentUuids);

        // sync pivot
        $employee->departments()->sync($departmentIds);
    }
}
