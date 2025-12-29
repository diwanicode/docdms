<?php
namespace App\Services\Business;
use App\Models\User;
use App\Models\Business; 
use App\Repositories\Country\CountryPermissionRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Str;
use Carbon\Carbon; 

class BusinessPermissionService
{
    public function __construct( public CountryPermissionRepository $countryPermissionRepository,
                                )
    {            
    }
    public function has(User $user, string $permissionSlug): bool
    {
        $employee = $user->businessEmployee;

        if (!$employee) {
            return false;
        }

        return $employee
            ->departments()
            ->whereHas('permissions', fn ($q) =>
                $q->where('slug', $permissionSlug)
            )
            ->exists();
    }
    
    public function getAllForUserPermissions(User $user): array
    {
        return $user->businessEmployee
            ->departments
            ->flatMap(fn ($d) => $d->permissions)
            ->pluck('slug')
            ->unique()
            ->values()
            ->toArray();
    }

    

}
