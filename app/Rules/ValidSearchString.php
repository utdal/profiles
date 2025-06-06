<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidSearchString implements ValidationRule
{
    protected int $minLength;

    /**
     * Set minimum length of search string (default value = 3).
     *
     */
    public function __construct(int $minLength = 3)
    {
        $this->minLength = $minLength;
    }

    /**
     * Validate the given attribute.
     *
     * @param string $attribute The name of the attribute under validation.
     * @param mixed $value The value of the attribute.
     * @param Closure(string): void $fail A callback function to report validation failure.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $label = "The search term";

        if (is_array($value)) {
            $fail("The {$attribute} must not be an array.");
            return;
        }

        if (!is_string($value)) {
            $fail("The {$attribute} must be a string.");
            return;
        }

        $value = trim($value);

        if (mb_strlen($value) < $this->minLength) {
            $fail("The {$attribute} must be at least {$this->minLength} characters long.");
            return;
        }

        if (mb_strlen($value) > 100) {
            $fail("The {$attribute} must not be longer than 100 characters.");
            return;
        }

        if (preg_match('/^[\pL\pN\s\'\-.,]+$/u', $value) !== 1) {
            $fail("The {$attribute} may only contain letters, numbers, spaces, apostrophes, dashes, commas, or periods.");
        }
    }

}
