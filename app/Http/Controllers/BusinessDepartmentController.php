<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Business\BusinessDepartmentService;  
use App\Http\Requests\Business\BusinessDepartmentRequest;
use App\Models\Business;
use App\Models\BusinessDepartment;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia; 

class BusinessDepartmentController extends Controller
{
      

    public function __construct( public BusinessDepartmentService $businessDepartmentService)
    {         
    }
    
     public function storeDepartment(Business $business, BusinessDepartmentRequest $businessDepartmentRequest)
    {
        try {
            $data = $businessDepartmentRequest->validated();
           
            Log::info('storeDepartment');
            $this->businessDepartmentService->storeBusinessDepartment($business, $data);
         
            $data = getTranslations($business->lang);
            $translations = $data['translations'];
            $message = $translations['businessDepartments']['successCreate'];
           //  $message = str_replace('{item}', $serviceName, $translations['messages']['success']['confirmBooking']);   
            return redirect()->back() ->with('success', $message);
        } catch (\Throwable $e) {
            Log::error('Error storing department: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return redirect()->back()->with('error', 'Failed to create department.');
        }
    }
    public function updateDepartment(Business $business,BusinessDepartment $businessDepartment, BusinessDepartmentRequest $businessDepartmentRequest)
    {
        try {
            $validated = $businessDepartmentRequest->validated();
        
            $this->businessDepartmentService->updateBusinessDepartment($business,$businessDepartment, $validated);
         
            $data = getTranslations($business->lang);
            $translations = $data['translations'];
            $message = $translations['businessDepartments']['successUpdate'];
               
            return redirect()->back() ->with('success', $message);
        } catch (\Throwable $e) {
            Log::error('Error updating department: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return redirect()->back()->with('error', 'Failed to update department.');
        }
    }
     public function deleteDepartment(Business $business,BusinessDepartment $businessDepartment)
    {
        try { 
            $this->businessDepartmentService->deleteBusinessDepartment($business,$businessDepartment);
         
            $data = getTranslations($business->lang);
            $translations = $data['translations'];
            $message = $translations['businessDepartments']['successDelete'];
               
            return redirect()->back() ->with('success', $message);
        } catch (\Throwable $e) {
            Log::error('Error deleting department: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return redirect()->back()->with('error', 'Failed to delete department.');
        }
    }

     
     
}
