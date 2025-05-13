<?php

namespace App\Http\Requests;

use App\Profile;
use App\Rules\AllowedProfileDataType;
use App\School;
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
            'person' => ['sometimes', 'string', 'regex:/^[a-zA-Z0-9.]+$/'],
            'search' => ['sometimes', 'string', 'alpha_num', 'min:3'],
            'search_names' => ['sometimes', 'string', 'alpha_num', 'min:3'],
            'info_contains' => ['sometimes', 'string', 'alpha_num', 'min:3'],
            'from_school' => [
                'sometimes',
                'string',
                'alpha',
                function ($attribute, $value, $fail) {
                    $allowed_schools = School::where('short_name', '!=', 'Other')->pluck('short_name')->toArray();
                    if(!in_array($value, $allowed_schools)){
                        $fail("Invalid value for $attribute.");
                    }
                }
            ],
            'tag' => ['sometimes', 'string', 'alpha_num', 'min:3'],
            'public' => 'sometimes|boolean',
            'with_data' => [
                'sometimes',
                'boolean',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        if (empty($this->person)) {
                            $fail("Parameter not allowed.");
                        }
                    }
                }
            ],
            'raw_data' => 'sometimes|boolean',
            'data_type' => [
                'sometimes',
                'filled',
                new AllowedProfileDataType(),
            ],
        ];
    }
}
