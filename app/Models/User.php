<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
     public function businessEmployees()
    {
        return $this->hasMany(BusinessEmployee::class);
    }
    public function businessEmployee()
    {
        //If a user can only belong to one business employee record
        return $this->hasOne(BusinessEmployee::class);
    }
    public function business(): HasOneThrough
    {
        return $this->hasOneThrough(
            Business::class,
            BusinessEmployee::class,'user_id', 'id', 'id','business_id'
        );
    }
    public function scopeOwnersOfBusiness(Builder $query, int $businessId): Builder
    {
        return $query->whereHas('businesses', function ($query) use ($businessId) {
            $query->where('business_id', $businessId)
                ->where('business_employees.is_owner', true);
        });
    }
}
