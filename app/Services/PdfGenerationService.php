<?php

namespace App\Services;

use App\Helpers\Contracts\PdfGenerationHelperContract;
use App\PdfGenerationResult;

class PdfGenerationService
{
    public function __construct(
        private PdfGenerationHelperContract $pdf_helper
    ) {}
    
    public function generatePdf(array $data, string $view, string $filename): PdfGenerationResult
    {
        $payload = [
            'view' => $view,
            'data' => $data,
            'filename' => $filename,
        ];
        
        // Call the actual pdf_helper (Browsershot OR Lambda)
        return $this->pdf_helper->generate($payload);
    }
}