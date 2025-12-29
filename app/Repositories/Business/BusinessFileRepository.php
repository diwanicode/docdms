<?php

namespace App\Repositories\Business;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\Business\BusinessFileResource;
use App\Models\User;
use App\Models\Business;
use App\Models\BusinessFile;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BusinessFileRepository
{
    public function findClientByUuid(Business $business, string $uuid)
    {
        return $business->businessClients()->where('client_key',$uuid)->first();
    }
    public function findFilesByBusiness(Business $business, bool $onlyColumns = false)
    {
        $query =  $business->businessFiles()->with(['fileType','fileCategory','fileStatus','business','businessClient','businessEmployee']);
        if ($onlyColumns) {
            return BusinessFileResource::columns($business);
        }
        $employees =$query->orderBy('created_at','DESC')->get();
        return BusinessFileResource::collection($employees)->toArray(request());
    }
    public function storeBusinessFile(Business $business, array $data, array $fileMeta)
    {
        $businessFile = $business->businessFiles()->create([
            'business_client_id'        => $data['business_client_id'],
            'business_employee_id'        => $data['business_employee_id'],
            'country_file_category_id'  => $data['country_file_category_id'],
            'country_file_type_id'      => $data['country_file_type_id'],
            'country_file_status_id'    => $data['country_file_status_id'],
               

            'file_key'       => Str::uuid(),
            'original_name'  => $fileMeta['original_name'],
            'stored_name'    => $fileMeta['stored_name'],
            'mime_type'      => $fileMeta['mime_type'],
            'size'           => $fileMeta['size'],
            'checksum'       => $fileMeta['checksum'],

            'document_date'  => $data['document_date'],
            'reference_number'=> $data['reference_number'],
            'source'         => 'manual',
        ]);
    }
    public function updateBusinessFile(BusinessFile $businessFile, array $data)
    {
        $businessFile->update([
            'business_client_id'       => $data['business_client_id'],
            'business_employee_id'     => $data['business_employee_id'] ?? null,
            'country_file_category_id' => $data['country_file_category_id'],
            'country_file_type_id'     => $data['country_file_type_id'] ?? null,
            'document_date'            => $data['document_date'] ?? null,
            'reference_number'         => $data['reference_number'] ?? null,
        ]);

    }
  
}
