<?php

namespace App\Services\File;

use App\Models\Business;
use App\Contracts\FileScanningInterface;
use App\Services\File\Dto\FileScanResult;
use RuntimeException;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

final class OcrFileScanner implements FileScanningInterface
{
    private string $tesseractPath;

    public function __construct()
    {
          $path = env('TESSERACT_PATH', 'tesseract');

        if (!file_exists($path) && !shell_exec("where $path") && !shell_exec("which $path")) {
            throw new RuntimeException("Tesseract executable not found at: {$path}");
        }

        $this->tesseractPath = $path;
    }
    public function scan(Business $business, string $filePath): FileScanResult
    {
       if (!file_exists($filePath)) {
            throw new RuntimeException("File not found: {$filePath}");
        }

        // Start timer for metadata
        $startTime = microtime(true);

        // Prepare Tesseract command (Bosnian)
        $command = [
            $this->tesseractPath,
            $filePath,
            'stdout',
            '-l', 'bos',        // Bosnian language
        ];

        // Run Tesseract using Symfony Process (more reliable than shell_exec)
        $process = new Process($command);

        try {
            $process->mustRun();
            $text = $process->getOutput();
        } catch (ProcessFailedException $e) {
            throw new RuntimeException("OCR process failed: " . $e->getMessage());
        }

        // Calculate processing time
        $processingTime = microtime(true) - $startTime;

        return new FileScanResult(
            text: trim($text),
            confidence: null, // CLI Tesseract doesn't provide confidence easily
            metadata: [
                'engine' => 'tesseract',
                'language' => 'bos',
                'processing_time' => $processingTime,
                'file' => $filePath,
            ]
        );
    }
}
