<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\HasImageUploads;
use Illuminate\Foundation\Http\FormRequest;

class ProfileImageRequest extends FormRequest
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
            'image' => $this->uploadedImageRules(),
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
            'image.max' => $this->uploadedImageMessages('max'),
        ];
    }

}
