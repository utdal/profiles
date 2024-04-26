<?php

namespace App\Http\Requests;

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
        $locale = app()->getLocale();

        return [
           'type' => 'required',
           'name' => [
                        'required',
                        'string',
                        'max:100',
                        Rule::unique('tags', 'name->'.$locale)
                                ->where('type', $this->input('type'))
                                ->ignore($this->input('id'))
                    ],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The tag name is required.',
            'name.unique' => 'The tag name provided already exists.',
            'name.max' => 'The tag name provided exceeds maximum length of 100 characters.',
        ]; 
    }

}
