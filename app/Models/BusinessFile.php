<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class BusinessFile extends Model
{
    use HasFactory;

    protected $table = 'business_files';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'business_id',
        'business_client_id',
        'country_file_category_id',
        'country_file_type_id',
        'country_file_status_id',
        'business_employee_id',
        'file_key',
        'original_name',
        'stored_name',
        'mime_type',
        'size',
        'document_date',
        'reference_number',
        'checksum',
        'source',
        'is_archived',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'document_date' => 'date',
        'is_archived' => 'boolean',
        'size' => 'integer',
    ];

    /**
     * Boot function to automatically generate UUID for file_key.
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->updated_at = null;
            if (empty($model->file_key)) {
                $model->file_key = Str::uuid()->toString();
            }
        });
    }

    /**
     * Relationships
     */
    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function businessClient()
    {
        return $this->belongsTo(BusinessClient::class);
    }

    public function fileCategory()
    {
        return $this->belongsTo(CountryFileCategory::class,'country_file_category_id');
    }

    public function fileType()
    {
        return $this->belongsTo(CountryFileType::class,'country_file_type_id');
    }

    public function fileStatus()
    {
        return $this->belongsTo(CountryFileStatus::class);
    }

    public function businessEmployee()
    {
        return $this->belongsTo(BusinessEmployee::class);
    }
     public function getRouteKeyName(): string
    {
        return 'file_key';
    }
}
