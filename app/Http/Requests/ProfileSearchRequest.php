<?php

namespace App\Http\Requests;

use App\Rules\ValidSearchString;
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
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'search' => new ValidSearchString(),
        ];
    }

    public function messages()
    {
        return [
            'search.string' => 'The search value must be a string',
        ]; 
    }
}
