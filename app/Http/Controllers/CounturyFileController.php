<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; 
use App\Models\Business;
use App\Models\BusinessClient;
use App\Models\BusinessFile;
use App\Services\Business\BusinessService;
use App\Services\Business\BusinessFileService;
use App\Http\Requests\Business\BusinessFileRequest;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CounturyFileController extends Controller
{
    public function __construct( public BusinessService $businessService,
                                 public BusinessFileService $businessFileService)
    {         
    }
    
    public function storeFile(Business $business, BusinessClient $businessClient, BusinessFileRequest $businessFileRequest)
    {
        try {
            $data = $businessFileRequest->validated();
            $user = auth()->user();
            $this->businessFileService->storeBusinessFileManual($business, $businessClient, $user, $data);
         
            $data = getTranslations($business->lang);
            $translations = $data['translations'];
            $message = $translations['businessFiles']['successCreate'];
               
            return redirect()->back()->with('success', $message);
        } catch (\Throwable $e) {
            Log::error('Error storing service: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return redirect()->back()->with('error', 'Failed to create file.');
        }
    }
    public function updateFile(Business $business,BusinessFile $businessFile, BusinessFileRequest $businessFileRequest)
    {
        try {
            $data = $businessFileRequest->validated();
            $user = auth()->user();
            $this->businessFileService->updateBusinessFileManual($business,$businessFile, $user, $data);
         
            $data = getTranslations($business->lang);
            $translations = $data['translations'];
            $message = $translations['businessFiles']['successUpdate'];
               
            return redirect()->back()->with('success', $message);
        } catch (\Throwable $e) {
            Log::error('Error storing service: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return redirect()->back()->with('error', 'Failed to create file.');
        }
    }

     public function deleteFileManual(Business $business,BusinessFile $businessFile)
    {
        try { 
            Log::info('deleteFileManual');
              Log::info($business);
            $this->businessFileService->deleteBusinessFileManual($business,$businessFile);
         
            $data = getTranslations($business->lang);
            $translations = $data['translations'];
            $message = $translations['businessFiles']['successDelete'];
               
            return redirect()->back() ->with('success', $message);
        } catch (\Throwable $e) {
            Log::error('Error deleting file 1: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return redirect()->back()->with('error', 'Failed to delete file.');
        }
    }
    public function downloadFile(Business $business, string $fileKey)
    {
        $file = BusinessFile::where('file_key', $fileKey)->firstOrFail();

        // Security check
        if ($file->business_id !== $business->id) {
            abort(403);
        }

        $clientId = $file->businessClient->client_key;
        $year = Carbon::parse($file->created_at)->year;
        $categorySlug = $file->fileCategory->slug;

        $path = "businesses/{$business->slug}/files/{$clientId}/{$year}/{$categorySlug}/{$file->stored_name}";

        return response()->download(
            storage_path('app/public/' . $path),
            $file->original_name
        );
    }


}
