<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Business\BusinessService;
use App\Services\Country\CountryFileService;
use App\Http\Resources\Business\BusinessResource; 
use App\Models\Business;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia; 

class BusinessController extends Controller
{
      

    public function __construct( public BusinessService $businessService,
                                 public CountryFileService $countryFileService)
    {         
    }
    
    public function show(Business $business)
    {
       // $businessDetails = $this->businessService->findBusinessBySlug($business);
      
        Log::info( 'show business');
        $businessDepartments = $this->businessService->getBusinessDepartments($business);        
        $businessDepartmentsColumns = $this->businessService->getBusinessDepartments($business,true);
        $businessEmployees =  $this->businessService->getBusinessEmployeesDropdown($business); 
        $permissons =   $this->businessService->getBusinessPermissions($business);
        return Inertia::render('Business/View',[
            'business'=>BusinessResource::make($business)->resolve(),
            'businessDepartments' =>$businessDepartments,
            'businessDepartmentsColumns' =>$businessDepartmentsColumns,
            'businessEmployees' =>$businessEmployees,
            'permissons'=> $permissons
        ]);
    }
     public function employees(Business $business)
    {   
        $businessEmployees =  $this->businessService->getBusinessEmployees($business);        
        $businessEmployeesColumns =  $this->businessService->getBusinessEmployees($business,true);  
        $businessDepartments = $this->businessService->getBusinessDepartments($business);       
        
        return Inertia::render('Business/Employees', [
            'business' => BusinessResource::make($business)->resolve(), 
            'businessEmployees' =>$businessEmployees, 
            'businessEmployeesColumns' =>$businessEmployeesColumns, 
            'businessDepartments' =>$businessDepartments, 
        ]);
    }
    public function clients(Business $business)
    {
        $businessClients = $this->businessService->getBusinessClients($business);        
        $businessClientsColumns = $this->businessService->getBusinessClients($business,true);
        $businessTypes = $this->businessService->getBusinessTypes($business); 
        
        return Inertia::render('Business/Clients', [
            'business' => BusinessResource::make($business)->resolve(),
            'businessClients' =>$businessClients,
            'businessClientsColumns' =>$businessClientsColumns,
            'businessTypes' => $businessTypes
        ]);
    }
    public function files(Business $business)
    {
        $businessClients = $this->businessService->getBusinessClientsDropdown($business);
        
        $businessFiles = $this->businessService->getBusinessFiles($business);  
        $businessFilesColumns = $this->businessService->getBusinessFiles($business,true);
        
        
        $fileCategories= $this->countryFileService->getCountryFileCategoriesByBusiness($business);
        $fileStatuses=[];
        $fileTypes=$this->countryFileService->getCountryFileTypesByBusiness($business);

        return Inertia::render('Business/Files', [
            'business' => BusinessResource::make($business)->resolve(),
            'businessClients' =>$businessClients,
            'businessFiles' =>$businessFiles,
            'businessFilesColumns' =>$businessFilesColumns,
            'fileCategories' =>$fileCategories,
            'fileStatuses' =>$fileTypes,
            'fileTypes' =>$fileTypes,
        ]);
    }
}
