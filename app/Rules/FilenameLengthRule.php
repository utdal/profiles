<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

class FilenameLengthRule implements ValidationRule
{
    public function __construct(private int $maxLength = 100) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $value instanceof UploadedFile) {
            return;
        }

        $name = $value->getClientOriginalName();

        if (strlen($name) > $this->maxLength) {
            $fail("The :attribute filename must not exceed {$this->maxLength} characters.");
        }
    }
}