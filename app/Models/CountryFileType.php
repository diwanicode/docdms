<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CountryFileType extends Model
{
    /** @use HasFactory<\Database\Factories\CountryFileTypeFactory> */
    use HasFactory;
     protected $fillable = [
        'slug',
        'name',
        'country_id',
        'country_file_category_id', 
    ];
    public function category()
    {
        return $this->belongsTo(CountryFileCategory::class, 'country_file_category_id');
    }
}
