<?php
namespace App\Http\Resources\Business;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class BusinessDepartmentResource extends JsonResource
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->key ?? null,
            'name' => $this->name ?? null,     
            'employees_count' => $this->employees->count() ?? null,
            'employees'=> $this->whenLoaded('employees', function () {
                return $this->employees->map(function ($employee)  {
                        return [
                            'id' => $employee->employee_key,
                            'name' => $employee->name ,
                        ];
                    });
                }),
            'permissions' => $this->whenLoaded('permissions', function () {
                return $this->permissions->flatMap(function ($perm) {
                    return $perm->countries->map(function ($country) use ($perm) {
                        return [
                            'id' => $perm->slug,
                            'name' => $country->resource . '-' . $country->action,
                        ];
                    });
                })->unique(fn($item) => $item['name'])->values();
            }),
        ];
    }
      public static function columns($business = null): array
    {
        // list of keys for table view
        $keys = [
            'name',            
            'employees', 
            'permissions', 
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
