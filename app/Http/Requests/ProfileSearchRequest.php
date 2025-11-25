<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileSearchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Search validation rules
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'search' => [
                'sometimes',
                'string',
                // letters, marks, numbers, spaces, commas, periods, dashes,
                // and non-consecutive apostrophes that are preceded and followed by a letter
                "regex:/^([\p{L}\p{M}\p{N}\p{Zs},\.\/&\(\)-]|(?<=[\p{L}])'(?!')(?=[\p{L}]))*$/u",
                'min:3',
                'max: 150',
            ],
        ];
    }

    public function messages()
    {
        return [
            'search.string' => 'The :attribute value must be a string',
            'search.regex' => 'The :attribute must only contain letters, numbers, and allowed characters.',
        ];
    }
}
