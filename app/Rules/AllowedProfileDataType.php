<?php

namespace App\Rules;

use App\Profile;
use Illuminate\Contracts\Validation\InvokableRule;

class AllowedProfileDataType implements InvokableRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        $allowed_data_types = Profile::apiRelations();

        if (is_string($value)) {
            $value = explode(';', $value);
        }
        else {
            $fail('The :attribute must be a string.');
        }

        foreach ($value as $data_type) {
            if (!in_array($data_type, $allowed_data_types)) {
                $fail('The :attribute is not valid.');
            }
        }
    }
}
