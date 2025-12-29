<?php
    namespace App\Services\Country;
 
    use App\Models\User;
    use App\Models\Business;
    use Carbon\Carbon;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Log;
    use App\Repositories\Business\BusinessRepository;
    use App\Repositories\Country\CountryRepository;
    use App\Repositories\Country\CountryTypeRepository;
    use App\Repositories\Country\CountryCategoryRepository;
    use App\Repositories\Business\BusinessEmployeeRepository;
    use App\Repositories\Business\BusinessClientRepository;

    class CountryFileService  
    {   
        public function __construct( public BusinessRepository $businessRepository,
                                     public CountryRepository $countryRepository,
                                     public CountryTypeRepository $countryTypeRepository,
                                     public CountryCategoryRepository $countryCategoryRepository,
                                     public BusinessEmployeeRepository $businessEmployeeRepository,
                                     public BusinessClientRepository $businessClientRepository )
        {            
        }
        public function getCountryFileCategoryBySlug(Business $business, string $slug)
        {
            return $this->countryCategoryRepository->findCountryFileCategoryBySlug($business->country_id,$slug);
        }      
        public function getCountryFileCategoriesByBusiness(Business $business)
        {
            return $this->countryCategoryRepository->findCountryFileCategoriesByBusiness($business);
        }
        public function getCountryFileTypeBySlug(Business $business, string $slug)
        {
        return $this->countryTypeRepository->findCountryFileTypeBySlug($business->country_id,$slug);
        } 
        public function getCountryFileTypesByBusiness(Business $business)
        {
            return $this->countryTypeRepository->findCountryFileTypesByBusiness($business);
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
        public function getBusinessClients(Business $business, bool $onlyColumns = false)
        {
            return $this->businessClientRepository->findClientsByBusiness($business,$onlyColumns);
        } 
    
    }
?>