<?php

namespace App\Helpers;

use App\Helpers\Contracts\PdfGenerationHelperContract;
use App\PdfGenerationResult;
use Illuminate\Support\Str;

class LambdaPdfHelper implements PdfGenerationHelperContract
{
    public function generate(array $payload): PdfGenerationResult
    {

        // Pdf::view('pdf.invoice', $data)
        //     ->onLambda()
        //     ->save('invoice.pdf');

        $filename = $payload['filename'] ?? '';
        $storage_path = $payload['storage_path'] ?? '';
        $job_id = $payload['job_id'] ?? (string) Str::ulid();

        return new PdfGenerationResult(
            success: true,
            filename: $filename,
            path: $storage_path,
            job_id: $job_id,
        );
    }
}