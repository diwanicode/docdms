<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionCountry extends Model
{
    /** @use HasFactory<\Database\Factories\PermissionCountryFactory> */
    use HasFactory;

   
    /**
     * Get the permission country record for a given country.
    */
    public static function forCountry(int $countryId)
    {
        return self::where('country_id', $countryId)->get();
    }
    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }
}
