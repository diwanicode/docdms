<?php

namespace App\Services\File\Dto;

final class FileScanResult
{
    //Data Transfer Object
    public function __construct(
        public readonly string $text,
        public readonly ?float $confidence = null,
        public readonly array $metadata = []
    ) {}
}
