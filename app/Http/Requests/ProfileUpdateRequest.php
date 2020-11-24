<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\HasImageUploads;
use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    use HasImageUploads;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'data.*.image' => $this->uploadedImageRules(),
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'data.*.image.max' => $this->uploadedImageMessages('max'),
        ];
    }
}
