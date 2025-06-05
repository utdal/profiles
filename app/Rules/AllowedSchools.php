<?php

namespace App\Rules;

use App\School;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AllowedSchools implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $allowed_schools = School::where('short_name', '!=', 'Other')
                                    ->pluck('short_name')
                                    ->toArray();

        if(!in_array($value, $allowed_schools)){
            $fail("Invalid value for school.");
        }

    }
}
