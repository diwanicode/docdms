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
use App\Models\CountryFileType;
use App\Models\CountryFileCategory; 
use Illuminate\Support\Str;  
use Carbon\Carbon;

class CountryRepository
{
    
    public function findClientsByBusiness(Business $business, bool $onlyColumns = false)
    {
        $query =  $business->businessClients()->with(['businessType','country','businessEmployee']);
        if ($onlyColumns) {
            return BusinessClientResource::columns($business);
        }
        $employees =$query->get();
        return BusinessClientResource::collection($employees)->toArray(request());  
    }
  
}
