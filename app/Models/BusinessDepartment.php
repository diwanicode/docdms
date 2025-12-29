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
        'name',
        'description',
    ];

  
    protected static function booted()
    {
        static::creating(function ($model) { 
            if (empty($model->key)) {
                $model->key = Str::uuid()->toString();
            }
        });
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
