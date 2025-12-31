<?php
namespace App\Http\Resources\Business;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class BusinessEmployeeResource extends JsonResource
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->employee_key ?? null,
            'name' => $this->name ?? null,            
            'owner' => $this->is_owner,
            'employee' => $this->is_working,
            'email' => $this->work_email,
            'number' => $this->phone_number, 
            'departments' => $this->whenLoaded('departments', function () {
                return $this->departments->map(fn($type) => [ 
                    'id' => $type->key,
                    'name' => $type->name,
                ]);
            }),
            'start_date' => $this->start_date 
                                ? Carbon::parse($this->start_date)->format('M j, Y') 
                                : null,
            'end_date' => $this->end_date 
                                ? Carbon::parse($this->end_date)->format('M j, Y') 
                                : null,
            'active' => $this->is_active
        ];
    }
      public static function columns($business = null): array
    {
        // list of keys for table view
        $keys = [
            'name',            
            'owner', 
            'email',
            'number',
            'departments',
            'start_date',
            'active', 
            'end_date',
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
