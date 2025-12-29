<?php
namespace App\Services\Business;
use App\Models\User;
use App\Models\Business;
use App\Models\BusinessFile;
use App\Models\BusinessEmployee;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Repositories\Business\BusinessEmployeeRepository;  
use App\Repositories\Business\BusinessEmployeeDepartmentRepository; 
use Illuminate\Support\Str;
use Carbon\Carbon; 

class BusinessEmployeeService
{
    public function __construct(  public BusinessEmployeeRepository $businessEmployeeRepository,
                                  public BusinessEmployeeDepartmentRepository $businessEmployeeDepartmentRepository)
    {            
    }
    public function storeBusinessEmployee(Business $business, array $data)
    { 
        try {
            DB::transaction(function () use ($business, &$data) {
                //  $tempPassword = Str::random(10);
                //  $temptPassword = Hash::make($tempPassword);
                
                $user = User::create([
                            'name' => $data['name'],
                            'email' => $data['email'],
                            'password' =>  Hash::make('Sarajevo71000'),  
                        ]);
                $employee = $this->businessEmployeeRepository->storeEmployee($business, $user, $data);
                 
                if (!empty($data['departments'])) {
                    $this->businessEmployeeDepartmentRepository->syncDepartments($business, $employee, $data['departments']);
                } 

            }); 
        } catch (\Exception $e) {
            Log::error('Error storing employee', [
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
    public function updateBusinessEmployee(Business $business, BusinessEmployee $businessEmployee, array $data)
    { 
        try {
            DB::transaction(function () use ($business, $businessEmployee, &$data) {
                $user = User::findOrFail($businessEmployee->user_id);
                $user->update([
                    'name'  => $data['name'],
                    'email' => $data['email'], 
                ]);
                Log::info('updateBusinessEmployee');
                $this->businessEmployeeRepository->updateEmployee($businessEmployee, $data);
                if (!empty($data['departments'])) {
                    $this->businessEmployeeDepartmentRepository->syncDepartments($business, $businessEmployee, $data['departments']);
                }
               
                Log::info('end updateBusinessEmployee');
            }); 
        } catch (\Exception $e) {
            Log::error('Error update employee', [
                'business_id' => $business->id, 
                'error' => $e->getMessage(),
            ]);
            return [
                'success' => false,
                'message' => 'Could not update the employeee. Please try again.',
                'error' => $e->getMessage(),
            ];
        }
    } 
    
   

}
