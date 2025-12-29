<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Business\BusinessClientService;  
use App\Services\Business\BusinessService;  
use App\Http\Requests\Business\BusinessClientRequest;
use App\Models\Business;
use App\Models\BusinessClient;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia; 

class BusinessClientController extends Controller
{
      

    public function __construct( public BusinessClientService $businessClientService,
                                 public BusinessService $businessService)
    {         
    }
    
     public function storeClient(Business $business, BusinessClientRequest $businessClientRequest)
    {
        try {
            $data = $businessClientRequest->validated();
           
            Log::info('store client');
            $user = auth()->user();
            $employee = $this->businessService->getBusinessEmployeeByUser($business, $user);
            $this->businessClientService->storeBusinessClient($business, $employee, $data);
         
            $data = getTranslations($business->lang);
            $translations = $data['translations'];
            $message = $translations['businessClients']['successCreate'];
           //  $message = str_replace('{item}', $serviceName, $translations['messages']['success']['confirmBooking']);   
            return redirect()->back() ->with('success', $message);
        } catch (\Throwable $e) {
            Log::error('Error storing clinet: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return redirect()->back()->with('error', 'Failed to create client.');
        }
    }
    public function updateClient(Business $business,BusinessClient $businessClient, BusinessClientRequest $businessClientRequest)
    {
        try {
            $validated = $businessClientRequest->validated();
        
            $this->businessClientService->updateBusinessClient($business,$businessClient, $validated);
         
            $data = getTranslations($business->lang);
            $translations = $data['translations'];
            $message = $translations['businessClients']['successUpdate'];
               
            return redirect()->back() ->with('success', $message);
        } catch (\Throwable $e) {
            Log::error('Error updating client: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return redirect()->back()->with('error', 'Failed to update client.');
        }
    } 

     
     
}
