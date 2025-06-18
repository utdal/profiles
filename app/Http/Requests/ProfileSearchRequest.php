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
     * Regex pattern to accept alphanumeric characters, periods, spaces, commas, and non-consecutive apostrophes that are preceded and followed by a letter
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'search' => ['sometimes', 'string', "regex:/^([a-zA-Z0-9\s,\.]|(?<=[a-zA-Z])'(?!')(?=[a-zA-Z]))*$/", 'min:3'],
        ];
    }

    public function messages()
    {
        return [
            'search.string' => 'The search value must be a string',
        ]; 
    }
}
