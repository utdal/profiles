<?php

namespace App\Http\Requests;

use App\Profile;
use App\Rules\AllowedProfileDataType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfilesApiRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'person' => 'sometimes|string',
            'search' => 'sometimes|string',
            'search_names' => 'sometimes|string',
            'info_contains' => 'sometimes|string',
            'from_school' => 'sometimes|string',
            'tag' => 'sometimes|string',
            'public' => 'sometimes|boolean',
            'with_data' => 'sometimes|boolean',
            'raw_data' => 'sometimes|boolean',
            'data_type' => [
                'sometimes',
                'filled',
                new AllowedProfileDataType(),
            ],
        ];
    }
}
