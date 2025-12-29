<?php
namespace App\Services\Business;
use App\Models\User;
use App\Models\Business;
use App\Models\BusinessFile;
use App\Models\BusinessDepartment;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Repositories\Business\BusinessDepartmentRepository;  
use App\Repositories\Business\BusinessDepartmentPermissionsRepository; 
use App\Repositories\Business\BusinessEmployeeDepartmentRepository; 
use Illuminate\Support\Str;
use Carbon\Carbon; 

class BusinessDepartmentService
{
    public function __construct(  public BusinessDepartmentRepository $businessDepartmentRepository,
                                  public BusinessDepartmentPermissionsRepository $businessDepartmentPermissionsRepository,
                                  public BusinessEmployeeDepartmentRepository $businessEmployeeDepartmentRepository)
    {            
    }
    public function storeBusinessDepartment(Business $business, array $data)
    { 
        try {
            DB::transaction(function () use ($business, &$data) {

                $department = $this->businessDepartmentRepository->storeBusinessDepartment($business, $data);
                Log::info('storeBusinessDepartment');
                  Log::info($data);
                if (!empty($data['employees'])) {
                    $this->businessEmployeeDepartmentRepository->syncEmployees($business, $department, $data['employees']);
                }

                if (!empty($data['permissions'])) {
                    $this->businessDepartmentPermissionsRepository->syncPermissions($department, $data['permissions']);
                }

            });

            return [
                'success' => true,
                'message' => 'Department stored successfully.'
            ];
        } catch (\Exception $e) {
            Log::error('Error storing department', [
                'business_id' => $business->id, 
                'error' => $e->getMessage(),
            ]);
            return [
                'success' => false,
                'message' => 'Could not store the department. Please try again.',
                'error' => $e->getMessage(),
            ];
        }
    }
    public function updateBusinessDepartment(Business $business, BusinessDepartment $businessDepartment, array $data)
    { 
        try {
            DB::transaction(function () use ($business, $businessDepartment, &$data) {

                $this->businessDepartmentRepository->updateBusinessDepartment($businessDepartment, $data);
                Log::info('storeBusinessDepartment');
                  Log::info($data);
                if (!empty($data['employees'])) {
                    $this->businessEmployeeDepartmentRepository->syncEmployees($business, $businessDepartment, $data['employees']);
                }

                if (!empty($data['permissions'])) {
                    $this->businessDepartmentPermissionsRepository->syncPermissions($businessDepartment, $data['permissions']);
                }

            });

            return [
                'success' => true,
                'message' => 'Department updated successfully.'
            ];
        } catch (\Exception $e) {
            Log::error('Error storupdateding department', [
                'business_id' => $business->id, 
                'error' => $e->getMessage(),
            ]);
            return [
                'success' => false,
                'message' => 'Could not store the department. Please try again.',
                'error' => $e->getMessage(),
            ];
        }
    }
    public function deleteBusinessDepartment(Business $business, BusinessDepartment $businessDepartment)
    { 
        try {
            DB::transaction(function () use ($business, $businessDepartment) {
                $businessDepartment->employees()->detach();
                $businessDepartment->permissions()->detach();
                $businessDepartment->delete();  
            });

            return [
                'success' => true,
                'message' => 'Department deleted successfully.'
            ];
        } catch (\Exception $e) {
            Log::error('Error deleting department', [
                'business_id' => $business->id, 
                'error' => $e->getMessage(),
            ]);
            return [
                'success' => false,
                'message' => 'Could not delete the department. Please try again.',
                'error' => $e->getMessage(),
            ];
        }
    }
    
   

}
