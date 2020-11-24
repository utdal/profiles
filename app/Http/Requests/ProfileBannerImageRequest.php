<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\HasImageUploads;
use Illuminate\Foundation\Http\FormRequest;

class ProfileBannerImageRequest extends FormRequest
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
            'banner_image' => $this->uploadedImageRules(),
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
            'banner_image.max' => $this->uploadedImageMessages('max'),
        ];
    }

}
