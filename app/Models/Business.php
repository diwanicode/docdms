<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    /** @use HasFactory<\Database\Factories\BusinessFactory> */
    use HasFactory;
    protected $fillable = ['name','vat_number','business_type_id','language_id',
    'country_id','country_region_id', 'logo','visible'];

    protected static function booted()
    {
      static::creating(function ($business) {
         $baseSlug = generateSlug($business->name);
         $slug = $baseSlug;
         $i = 1;
         while (Business::where('slug', $slug)->exists()) {
               $slug = $baseSlug . '-' . $i++;
         }
         $business->slug = $slug;
      });
    }
    public function businessEmployees()
    {
        return $this->hasMany(BusinessEmployee::class);
    }
    public function businessClients()
    {
        return $this->hasMany(BusinessClient::class);
    }
    public function businessType()
    {
        return $this->belongsTo(BusinessType::class, 'business_type_id');
    }
    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }
    public function countryFileStatuses()
    {
        return $this->hasMany(CountryFileStatus::class);
    }
    public function businessFiles()
    {
        return $this->hasMany(BusinessFile::class);
    }
    public function businessDepartments()
    {
        return $this->hasMany(BusinessDepartment::class);
    }
    public function getLangAttribute()
    {
        return $this->language?->code ?? 'bs';
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
