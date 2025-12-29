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
use Illuminate\Support\Str;  
use Carbon\Carbon;

class CountryTypeRepository
{
    public function findCountryFileTypeBySlug(int $countryId, string $slug)
    {
        return CountryFileType::where('slug',$slug)->where('country_id',$countryId)->first();        
    }
    public function findCountryFileTypesByBusiness(Business $business)
    {
        $query = CountryFileType::where('country_id',$business->country_id)->get();

        return CountryFileTypeResource::collection($query)->toArray(request());  
    }
  
}
