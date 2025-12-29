<?php
namespace App\Services\Business;
use App\Models\User;
use App\Models\Business;
use App\Models\BusinessClient;
use App\Models\BusinessEmployee;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Repositories\Business\BusinessClientRepository;  
use App\Repositories\Business\BusinessEmployeeDepartmentRepository; 
use Illuminate\Support\Str;
use Carbon\Carbon; 

class BusinessClientService
{
    public function __construct(  public BusinessClientRepository $businessClientRepository,
                                  public BusinessEmployeeDepartmentRepository $businessEmployeeDepartmentRepository)
    {            
    }
    public function storeBusinessClient(Business $business, BusinessEmployee $businessEmployee, array $data)
    { 
        try {
            DB::transaction(function () use ($business,$businessEmployee, &$data) {
                //  $tempPassword = Str::random(10);
                //  $temptPassword = Hash::make($tempPassword);
                Log::info('storeBusinessClient');
                 Log::info($businessEmployee);
                $user = User::create([
                            'name' => $data['contact'],
                            'email' => $data['email'],
                            'password' =>  Hash::make('Sarajevo71000'),  
                        ]);
                $this->businessClientRepository->storeClient($business, $businessEmployee, $user, $data);
                

            }); 
        } catch (\Exception $e) {
            Log::error('Error storing client', [
                'business_id' => $business->id, 
                'error' => $e->getMessage(),
            ]);
            return [
                'success' => false,
                'message' => 'Could not store the client. Please try again.',
                'error' => $e->getMessage(),
            ];
        }
    }
    public function updateBusinessClient(Business $business, BusinessClient $businessClient, array $data)
    { 
        try {
            DB::transaction(function () use ($business, $businessClient, &$data) {
                $user = User::findOrFail($businessClient->user_id);
                $user->update([
                    'name'  => $data['contact'],
                    'email' => $data['email'], 
                ]);
                Log::info('businessClient');
                $this->businessClientRepository->updateClient($businessClient, $data);
  
                Log::info('end businessClient');
            }); 
        } catch (\Exception $e) {
            Log::error('Error update client', [
                'business_id' => $business->id, 
                'error' => $e->getMessage(),
            ]);
            return [
                'success' => false,
                'message' => 'Could not update the client. Please try again.',
                'error' => $e->getMessage(),
            ];
        }
    } 
    
   

}
