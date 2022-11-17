<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TagsUpdateRequest extends FormRequest
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
            'model' => 'required|in:App\Profile,App\Student',
            'id' => 'required|gt:0',
            'tags' => 'sometimes|',
                    function ($attribute, $value, $fail) {
                        if (!is_array($value) && !is_string($value)) {
                            $fail('The '.$attribute.' is invalid. Press the "enter" key or type a comma (,) after each new tag.');
                        }
                    },
        ];
    }

}
