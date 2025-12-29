<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BusinessEmployee extends Model
{
    protected $fillable = [
        'business_id',
        'employee_key',
        'user_id',
        'name',
        'is_owner',
        'work_email',
        'phone_number',
        'is_working',
        'start_date',
        'end_date',
        'is_active',
    ];

    /**
     * GUARANTEE employee_key on EVERY new model instance
     * (seeders, relations, factories, runtime)
     */
    public function __construct(array $attributes = [])
    {
        if (! array_key_exists('employee_key', $attributes)) {
            $attributes['employee_key'] = (string) Str::uuid();
        }

        parent::__construct($attributes);
    }

    /**
     * Extra safety for edge cases
     */
    protected static function booted()
    {
        static::creating(function ($employee) {
            if (empty($employee->employee_key)) {
                $employee->employee_key = (string) Str::uuid();
            }
        });
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function departments()
    {
        return $this->belongsToMany(
            BusinessDepartment::class,
            'business_employee_departments'
        );
    } 
    public function permissions()
    {
        return $this->hasManyThrough(
            Permission::class,
            BusinessDepartmentPermission::class,
            'business_department_id',
            'id',
            'id',
            'permission_id'
        );
    }
 
    public function getRouteKeyName(): string
    {
        return 'employee_key';
    }
}
