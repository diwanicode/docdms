<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BusinessDepartment extends Model
{
    /** @use HasFactory<\Database\Factories\BusinessDepartmentFactory> */
    use HasFactory;

     protected $fillable = [
        'business_id', 
        'key',
        'name',
        'description',
    ];

  
    /**
     * GUARANTEE key on EVERY new model instance
     * (seeders, relations, factories, runtime)
     */
    public function __construct(array $attributes = [])
    {
        if (! array_key_exists('key', $attributes)) {
            $attributes['key'] = (string) Str::uuid();
        }

        parent::__construct($attributes);
    }
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'business_department_permissions');
    }
    public function employees()
    {
        return $this->belongsToMany(BusinessEmployee::class, 'business_employee_departments');
    }
}
