<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CountryFileStatus extends Model
{
    /** @use HasFactory<\Database\Factories\CountryFileStatusFactory> */
    use HasFactory;
      protected $fillable = [
        'business_id',
        'key',
        'name',
        'color_light',
        'color_dark',
        'order',
        'country_id',
        'country_file_category_id', 
    ];
    
     public function __construct(array $attributes = [])
    {
        if (! array_key_exists('key', $attributes)) {
            $attributes['key'] = (string) Str::uuid();
        }

        parent::__construct($attributes);
    }

    /**
     * Extra safety for edge cases
     */
    protected static function booted()
    {
        static::creating(function ($fileStatus) {
            if (empty($fileStatus->key)) {
                $fileStatus->key = (string) Str::uuid();
            }
        });
    }
}
