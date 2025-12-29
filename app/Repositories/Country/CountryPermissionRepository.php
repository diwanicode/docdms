<?php

namespace App\Repositories\Country;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Hash;

use App\Http\Resources\Country\CountryFileTypeResource;
use App\Models\User;
use App\Models\Business;
use App\Models\Country;
use App\Models\PermissionCountry;
use App\Models\CountryFileCategory; 
use Illuminate\Support\Str;  
use Carbon\Carbon;

class CountryPermissionRepository
{
    
    public function findPermissionsByBusiness(Business $business, bool $onlyColumns = false)
    {
        $countryId = $business->country_id;
       
        return  PermissionCountry::forCountry($business->country_id)
                ->map(function ($permCountry) {
                    return [
                        'id' => $permCountry->permission->slug,
                        'name' => $permCountry->resource . '-' . $permCountry->action,
                    ];
                });
       
    }
  
}
