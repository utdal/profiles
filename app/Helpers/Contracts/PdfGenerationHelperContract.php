<?php

namespace App\Helpers\Contracts;

use App\PdfGenerationResult;

interface PdfGenerationHelperContract {

    public function generate(array $payload): PdfGenerationResult;

}