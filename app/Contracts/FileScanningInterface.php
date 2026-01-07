<?php

namespace App\Contracts;
 
use App\Models\Business;
use App\Models\BusinessEmployee; 
use Carbon\Carbon;
use \Illuminate\Support\Collection; 
use App\Services\File\Dto\FileScanResult;

interface FileScanningInterface
{
     /**
     * Scans a file and returns extracted text + metadata
     */
    public function scan(Business $business, string $filePath): FileScanResult;
}
