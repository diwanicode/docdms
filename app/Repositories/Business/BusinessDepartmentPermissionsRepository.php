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
use App\Models\Permission;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BusinessDepartmentPermissionsRepository
{
    public function findDepartmentPermissonByUuid(Business $business, string $uuid)
    {
        return $business->businessDepartments()->where('key',$uuid)->first();
    }
    public function findPermissionIdsBySlugs( array $slugs)
    {
        return Permission::whereIn('slug',$slugs) 
               ->pluck('id')
               ->toArray();
    }
    public function syncPermissions(BusinessDepartment $department, array $permissions): void
    {
          $permissionSlugs = collect($permissions)
            ->pluck('id')
            ->filter()
            ->values()
            ->toArray();

        if (empty($permissionSlugs)) {
            $department->permissions()->sync([]);
            return;
        }

        // convert UUIDs → internal IDs (business-safe)
        $permissionIds = $this->findPermissionIdsBySlugs( $permissionSlugs);

        $department->permissions()->sync($permissionIds);
    } 
}
