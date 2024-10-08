<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MinWords implements ValidationRule
{
    protected $minWords;

    public function __construct($minWords)
    {
        $this->minWords = $minWords;
    }
    
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (str_word_count($value) < $this->minWords) {
            $fail("The :attribute field must have at least {$this->minWords} words.");
        }
    }
}
