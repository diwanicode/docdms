<?php
    namespace App\Services\Business;
 
    use App\Models\User;
    use App\Models\Business;
    use Carbon\Carbon;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Log;
    use App\Repositories\Business\BusinessRepository;
    use App\Repositories\Business\BusinessTypeRepository;
    use App\Repositories\Business\BusinessFileRepository;
    use App\Repositories\Business\BusinessEmployeeRepository;
    use App\Repositories\Business\BusinessClientRepository;
    use App\Repositories\Business\BusinessDepartmentRepository;
    use App\Repositories\Country\CountryPermissionRepository; 

    class BusinessService   
    {   
        public function __construct( public BusinessRepository $businessRepository,
                                     public BusinessTypeRepository $businessTypeRepository,
                                     public BusinessEmployeeRepository $businessEmployeeRepository,
                                     public BusinessClientRepository $businessClientRepository,
                                     public BusinessDepartmentRepository $businessDepartmentRepository, 
                                     public BusinessFileRepository $businessFileRepository,
                                     public CountryPermissionRepository $countryPermissionRepository)
        {            
        }
        public function getBusinessBySlug(string $slug)
        {
            return $this->businessRepository->findBusinessBySlug($slug);
        }
        public function getBusinessDetails(Business $business)
        {
            //TODO Emina add logo and landscape picture
            return $this->businessRepository->findBusinessDetailsBySlug($business);
        } 
        public function getBusinessEmployees(Business $business, bool $onlyColumns = false)
        {
            return $this->businessEmployeeRepository->findEmployeesByBusiness($business,$onlyColumns );
        } 
        public function getBusinessEmployeeByUser(Business $business, User $user)
        {
            return $this->businessEmployeeRepository->findEmployeesByUser($business,$user );
        } 
        public function getBusinessClientByUuid(Business $business,string $uuid)
        {
            return $this->businessClientRepository->findClientByUuid($business,$uuid);
        }
        public function getBusinessClients(Business $business, bool $onlyColumns = false)
        {
            return $this->businessClientRepository->findClientsByBusiness($business,$onlyColumns);
        } 
         public function getBusinessDepartments(Business $business, bool $onlyColumns = false)
        {
            return $this->businessDepartmentRepository->findDepartmentsByBusiness($business,$onlyColumns);
        }
        public function getBusinessFiles(Business $business, bool $onlyColumns = false)
        {
            return $this->businessFileRepository->findFilesByBusiness($business,$onlyColumns);
        } 
        public function getBusinessTypes(Business $business)
        {
            return $this->businessTypeRepository->findBusinessTypes($business);
        } 
        public function getBusinessPermissions(Business $business)
        {
            return $this->countryPermissionRepository->findPermissionsByBusiness($business);
        }
        
    }
?>