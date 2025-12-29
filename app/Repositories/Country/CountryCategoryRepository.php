<?php

namespace App\Repositories\Country;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\Country\CountryFileCategoryResource; 
use App\Models\User;
use App\Models\Business;
use App\Models\Country;
use App\Models\CountryFileType;
use App\Models\CountryFileCategory; 
use Illuminate\Support\Str;  
use Carbon\Carbon;

class CountryCategoryRepository
{
    public function findCountryFileCategoryBySlug(int $countryId, string $slug)
    {
        return CountryFileCategory::where('slug',$slug)->where('country_id',$countryId)->first();        
    }
    public function findCountryFileCategoriesByBusiness(Business $business)
    {
        $query = CountryFileCategory::where('country_id',$business->country_id)->get();

        return CountryFileCategoryResource::collection($query)->toArray(request());  
    }
  
}
