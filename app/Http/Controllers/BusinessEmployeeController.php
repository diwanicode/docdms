<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Business\BusinessEmployeeService;  
use App\Http\Requests\Business\BusinessEmployeeRequest;
use App\Models\Business;
use App\Models\BusinessEmployee;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia; 

class BusinessEmployeeController extends Controller
{
      

    public function __construct( public BusinessEmployeeService $businessEmployeeService)
    {         
    }
    
     public function storeEmployee(Business $business, BusinessEmployeeRequest $businessEmployeeRequest)
    {
        try {
            $data = $businessEmployeeRequest->validated();
           
            Log::info('store employeee');
            $this->businessEmployeeService->storeBusinessEmployee($business, $data);
         
            $data = getTranslations($business->lang);
            $translations = $data['translations'];
            $message = $translations['businessEmployees']['successCreate'];
           //  $message = str_replace('{item}', $serviceName, $translations['messages']['success']['confirmBooking']);   
            return redirect()->back()->with('success', $message);
        } catch (\Throwable $e) {
            Log::error('Error storing employee: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return redirect()->back()->with('error', 'Failed to create employee.');
        }
    }
    public function updateEmployee(Business $business,BusinessEmployee $businessEmployee, BusinessEmployeeRequest $businessEmployeeRequest)
    {
        try {
            $validated = $businessEmployeeRequest->validated();
        
            $this->businessEmployeeService->updateBusinessEmployee($business,$businessEmployee, $validated);
         
            $data = getTranslations($business->lang);
            $translations = $data['translations'];
            $message = $translations['businessEmployees']['successUpdate'];
               
            return redirect()->back()->with('success', $message);
        } catch (\Throwable $e) {
            Log::error('Error updating employee: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return redirect()->back()->with('error', 'Failed to update employee.');
        }
    } 

     
     
}
