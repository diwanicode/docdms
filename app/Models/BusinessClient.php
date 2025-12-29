<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BusinessClient extends Model
{
    /** @use HasFactory<\Database\Factories\BusinessClientFactory> */
    use HasFactory;
      protected $fillable = [
        'business_id',
        'client_key',
        'name',
        'contact',
        'vat_number',
        'user_id',
        'email',
        'number',
        'business_type_id',
        'business_employee_id',
        'start_date',
        'end_date',
        'country_id',
        'city',
        'address',
        'is_active',
        'note'
    ];

    /**
     * GUARANTEE client_key on EVERY new model instance
     * (seeders, relations, factories, runtime)
     */
    public function __construct(array $attributes = [])
    {
        if (! array_key_exists('client_key', $attributes)) {
            $attributes['client_key'] = (string) Str::uuid();
        }

        parent::__construct($attributes);
    }

    /**
     * Extra safety for edge cases
     */
    protected static function booted()
    {
        static::creating(function ($employee) {
            if (empty($employee->client_key)) {
                $employee->client_key = (string) Str::uuid();
            }
        });
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function businessEmployee()
    {
        return $this->belongsTo(businessEmployee::class);
    }
    public function businessType()
    {
        return $this->belongsTo(BusinessType::class, 'business_type_id');
    }
    public function country()
    {
        return $this->belongsTo(Country::class, 'business_type_id');
    }
    public function getRouteKeyName(): string
    {
        return 'client_key';
    }
}
