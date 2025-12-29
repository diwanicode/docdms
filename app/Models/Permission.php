<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    /** @use HasFactory<\Database\Factories\PermissionFactory> */
    use HasFactory;
    protected $fillable = ['slug'];

    public function countries()
    {
        return $this->hasMany(PermissionCountry::class);
    }
     public function departments()
    {
        return $this->belongsToMany(
            BusinessDepartment::class,
            'business_department_permissions'
        )->withTimestamps()
         ->withPivot('deleted_at');
    }
}
