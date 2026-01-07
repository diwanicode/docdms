<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\File\OcrFileScanner;
use RuntimeException;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Business;

class OcrFileScannerTest extends TestCase
{
    use RefreshDatabase;
  
    protected function setUp(): void
    {
        parent::setUp();   
        $this->business = Business::factory()->create();
    }
    /** @test */
    public function it_can_scan_a_bosnian_document()
    {
        // Path to your test image (make sure this file exists!)
        $filePath = base_path('\public\images\Screenshot_test_bs.png');

        if (!file_exists($filePath)) {
            $this->markTestSkipped('Test file does not exist: ' . $filePath);
        }

        $scanner = new OcrFileScanner(); // uses tesseract from PATH

        $result = $scanner->scan( $this->business, $filePath);
        Log::info('OcrFileScannerTest');
        Log::info($result->text);
        // Assertions
        $this->assertNotEmpty($result->text, 'OCR text should not be empty');
        $this->assertArrayHasKey('engine', $result->metadata);
        $this->assertEquals('tesseract', $result->metadata['engine']);
        $this->assertArrayHasKey('language', $result->metadata);
        $this->assertEquals('bos', $result->metadata['language']);
        $this->assertArrayHasKey('processing_time', $result->metadata);
   
    }

    /** @test */
    public function it_throws_exception_for_missing_file()
    {
        $this->expectException(RuntimeException::class);

        $scanner = new OcrFileScanner();
        $scanner->scan($this->business, base_path('tests/Fixtures/missing_file.png'));
    }
}
