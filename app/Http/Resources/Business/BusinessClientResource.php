<?php
namespace App\Http\Resources\Business;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class BusinessClientResource extends JsonResource
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->client_key ?? null,
            'name' => $this->name ?? null,        
            'contact' => $this->contact,
            'vat_number' => $this->vat_number,
            'email' => $this->email,
            'number' => $this->number,
            'start_date' => $this->start_date 
                                ? Carbon::parse($this->start_date)->format('M j, Y') 
                                : null,
            'end_date' => $this->end_date, 
            'active' => $this->is_active,
            'business_type_id' =>   $this->businessType->id  ?? null,
            'type' =>   $this->businessType->short  ?? null,
            'employee' =>   $this->businessEmployee->name  ?? null,
            'country' =>   $this->country->name  ?? null,
            'city' =>   $this->city,
            'address' =>   $this->address,
            'note' =>   $this->note ?? null,
        ];
    }
      public static function columns($business = null): array
    {
        // list of keys for table view
        $keys = [
            'name',            
            'contact',  
            'employee',       
            'start_date',
            'active', 
            'note',
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
