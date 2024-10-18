<?php

namespace App\Http\Requests;

use App\Rules\TagNameUniqueness;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TagUpdateRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {

        return [
           'type' => 'required',
           'name' => [
                        'required',
                        'string',
                        new TagNameUniqueness,
                    ],
        ];
    }

    public function messages()
    {
        return [
        ]; 
    }

}
