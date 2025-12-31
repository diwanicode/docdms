<?php
namespace App\Services\Business;
use App\Models\User;
use App\Models\Business;
use App\Models\BusinessFile;
use App\Models\BusinessClient;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Repositories\Business\BusinessFileRepository;
use App\Services\Business\BusinessService;
use App\Services\Business\BusinessClientService;
use App\Services\Country\CountryFileService;
use Illuminate\Support\Str;
use Carbon\Carbon; 

class BusinessFileService
{
    public function __construct(  public BusinessFileRepository $businessFileRepository,
                                  public BusinessService $businessService,
                                  public BusinessClientService $businessClientService,
                                  protected CountryFileService $countryFileService)
    {            
    }
    public function storeBusinessFileManual(Business $business, BusinessClient $businessClient, User $user, array $data)
    { 
        try {
            DB::transaction(function () use ($business, $businessClient, $user, &$data) {

                // Upload the file
                $fileMeta = $this->uploadFile($business, $businessClient->client_key, $data);
            
                // Resolve category & employee
                $fileCategory = $this->countryFileService->getCountryFileCategoryBySlug($business, $data['country_file_category_id']);
                $employee = $this->businessService->getBusinessEmployeeByUser($business, $user);
                $fileTypeId=null;
                if($data['country_file_type_id']){
                    $fileType = $this->countryFileService->getCountryFileTypeBySlug($business, $data['country_file_type_id']);
                    $fileTypeId = $fileType->id;
                }

                // Merge necessary info
                $data['country_file_category_id'] = $fileCategory->id;
                $data['country_file_type_id'] = $fileTypeId;
                $data['business_client_id'] = $businessClient->id;
                $data['business_employee_id'] = $employee->id;
                $data['country_file_status_id'] = 1;

                // Store in repository
                $this->businessFileRepository->storeBusinessFile($business, $data, $fileMeta);
            });

            return [
                'success' => true,
                'message' => 'File uploaded and stored successfully.'
            ];
        } catch (\Exception $e) {
            Log::error('Error storing business file', [
                'business_id' => $business->id,
                'business_client_id' => $businessClient->id,
                'error' => $e->getMessage(),
            ]);

            // Optionally, delete uploaded file here if $fileMeta exists

            return [
                'success' => false,
                'message' => 'Could not store the file. Please try again.',
                'error' => $e->getMessage(),
            ];
        }
    }
    public function updateBusinessFileManual(Business $business,BusinessFile $file, User $user, array $data)
    { 
        try {
            $businessClient = $this->businessService->getBusinessClientByUuid($business,$data['business_client_id']);
            DB::transaction(function () use ($business, $businessClient, $file, $user, &$data) {

                // Upload the file
                if (!empty($data['file']) && $data['file'] instanceof UploadedFile) {
                    $this->replaceFile(
                        $business,
                        $file,
                        $businessClient->client_key,
                        $data
                    );
                }
             
                
                // Resolve category & employee
                $fileCategory = $this->countryFileService->getCountryFileCategoryBySlug($business, $data['country_file_category_id']);
                $employee = $this->businessService->getBusinessEmployeeByUser($business, $user);
                $fileTypeId=null;
                if($data['country_file_type_id']){
                    $fileType = $this->countryFileService->getCountryFileTypeBySlug($business, $data['country_file_type_id']);
                    $fileTypeId = $fileType->id;
                }

                // Merge necessary info
                $data['country_file_category_id'] = $fileCategory->id;
                $data['country_file_type_id'] = $fileTypeId;
                $data['business_client_id'] = $businessClient->id;
                $data['business_employee_id'] = $employee->id; //TODO Emina add role permission
                // Store in repository
                $this->businessFileRepository->updateBusinessFile($file, $data);
            });

            return [
                'success' => true,
                'message' => 'File uploaded and updated successfully.'
            ];
        } catch (\Exception $e) {
            Log::error('Error updating business file', [
                'business_id' => $business->id,
                'business_client_id' => $businessClient->id,
                'error' => $e->getMessage(),
            ]);

            // Optionally, delete uploaded file here if $fileMeta exists

            return [
                'success' => false,
                'message' => 'Could not update the file. Please try again.',
                'error' => $e->getMessage(),
            ];
        }
    }
    public function deleteBusinessFileManual(Business $business, BusinessFile $file)
    { 
        try {
            Log::info('delete');
              Log::info($business);
            Log::info($file);
            DB::transaction(function () use ($business, $file) { 
                $client = $this->businessClientService->getBusinessClientById($business, $file->business_client_id);
                $this->deleteFileFromStorage($business, $file, $client->client_key);
                $file->delete();  
            });

            return [
                'success' => true,
                'message' => 'File deleted successfully.'
            ];
        } catch (\Exception $e) {
            Log::error('Error deleting file 2', [ 
                'error' => $e->getMessage(),
            ]);
            return [
                'success' => false,
                'message' => 'Could not delete the file. Please try again.',
                'error' => $e->getMessage(),
            ];
        }
    }
    public function uploadFile(Business $business, string $clientId, array $data): array 
    {
        $year = now()->year;
        $file= $data['file'];
        $categorySlug = $data['country_file_category_id'];

        $hash = Str::uuid()->toString();
        $extension = $file->getClientOriginalExtension();
        $filename = "{$hash}.{$extension}";

        $path = "businesses/{$business->slug}/files/{$clientId}/{$year}/{$categorySlug}";

        $storedPath = $file->storeAs($path, $filename, 'public');

        return [
            'stored_path'   => $storedPath,
            'stored_name'   => $filename,
            'original_name' => $file->getClientOriginalName(),
            'mime_type'     => $file->getMimeType(),
            'size'          => $file->getSize(),
            'checksum'      => hash_file('sha256', $file->getRealPath()),
        ];
    }
    public function replaceFile( Business $business,BusinessFile $businessFile, string $clientKey,array $data ): void
    {
        // delete old file safely
        $this->deleteFileFromStorage($business, $businessFile, $clientKey);
        // upload new file
        $fileMeta = $this->uploadFile($business, $clientKey, $data);

        // update record
        $businessFile->update([
            'stored_name'   => $fileMeta['stored_name'],
            'original_name' => $fileMeta['original_name'],
            'mime_type'     => $fileMeta['mime_type'],
            'size'          => $fileMeta['size'],
            'checksum'      => $fileMeta['checksum'],
        ]);
    }
    public function deleteFileFromStorage(Business $business, BusinessFile $businessFile, string $clientKey)
    {
        // delete old file safely
        Storage::disk('public')->delete(
            "businesses/{$business->slug}/files/{$clientKey}/"
            . Carbon::parse($businessFile->created_at)->year
            . "/{$businessFile->fileCategory->slug}/{$businessFile->stored_name}"
        );
    }

}
