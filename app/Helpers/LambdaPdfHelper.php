<?php

namespace App\Helpers;

use App\Helpers\Contracts\PdfGenerationHelperContract;
use App\PdfGenerationResult;

class LambdaPdfHelper implements PdfGenerationHelperContract
{
    public function generate(array $payload)//: PdfGenerationResult
    {

        // Pdf::view('pdf.invoice', $data)
        //     ->onLambda()
        //     ->save('invoice.pdf');

        // return new PdfGenerationResult(
            // success: true,
            // filename: $filename,
            // path: $storage_path,
        // );
    }
}