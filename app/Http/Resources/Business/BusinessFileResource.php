<?php
namespace App\Http\Resources\Business;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class BusinessFileResource extends JsonResource
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->file_key ?? null,
            'name' => $this->original_name ?? null, 
            'file_url' => route('business.files.download', [
                            'business' => $this->business->slug,
                            'businessFile'  => $this->file_key,
                        ]),        
            'client' => $this->businessClient?->name ?? null,
            'business_client_id' => $this->businessClient?->client_key ?? null,
            'employee' => $this->businessEmployee?->name ?? null,
            'business_employee_id' => $this->businessEmployee?->employee_key ?? null,
            'file_category' => $this->fileCategory?->name ?? null,
            'country_file_category_id' => $this->fileCategory?->slug ?? null,
            'file_status' => $this->fileStatus?->name ?? null,
            'country_file_status_id' => $this->fileStatus?->key ?? null,
            'file_type' =>  $this->fileType?->name ?? null,
            'country_file_type_id' =>  $this->fileType?->slug ?? null,
            'document_date' => $this->document_date,
            'reference_number' => $this->reference_number,
            'created_at' => $this->created_at 
                                ? Carbon::parse($this->created_at)->format('M j, Y H:s') 
                                : null,
            'updated_at' => $this->updated_at 
                                ? Carbon::parse($this->updated_at)->format('M j, Y H:s') 
                                : null,
  
        ];
    }
      public static function columns($business = null): array
    {
        // list of keys for table view
        $keys = [
            'client',  
            'name',            
            'file_category',  
            'file_type',     
            // 'document_date', 
            // 'reference_number',
            'employee',
            'created_at',
            'updated_at'
        ];

        if (function_exists('mapColumnsWithLabels') && $business) {
            return mapColumnsWithLabels($keys, $business);
        }

        // fallback: generate labels automatically
        return collect($keys)->map(fn ($key) => [
            'field'    => $key,
            'key'      => $key,
            'label'    => ucfirst(str_replace('_', ' ', $key)), // "lang_code" -> "Lang code"
            'sortable' => true,
        ])->toArray();
    }
}
