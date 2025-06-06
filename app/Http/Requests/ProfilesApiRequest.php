<?php

namespace App\Http\Requests;

use App\Rules\AllowedProfileDataType;
use App\Rules\AllowedSchools;
use App\Rules\ValidSearchString;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

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
            'person' => ['sometimes', 'string', 'regex:/^[a-zA-Z0-9.;]+$/'],
            'search' => new ValidSearchString(),
            'search_names' => new ValidSearchString(),
            'info_contains' => new ValidSearchString(),
            'from_school' => [
                'sometimes',
                'string',
                'alpha',
                    new AllowedSchools(),
            ],
            'tag' => ['sometimes', 'string', 'alpha_num', 'min:3'],
            'public' => 'sometimes|boolean',
            'with_data' => [
                'sometimes',
                'boolean',
               $this->validateWithData(),
            ],
            'raw_data' => 'sometimes|boolean',
            'data_type' => [
                'sometimes',
                'filled',
                new AllowedProfileDataType(),
            ],
        ];
    }

    public function validateWithData()
    {
        return function ($attribute, $value, $fail) {
            if ($value && empty($this->person)) {
                $fail('Invalid parameter.');
            }
        };
    }

    protected function passedValidation(): void
    {
        $allowedKeys = array_keys($this->rules());

        $extraKeys = collect($this->all())
            ->keys()
            ->diff($allowedKeys);

        if ($extraKeys->isNotEmpty()) {
            throw ValidationException::withMessages([
                'extra_parameters' => 'Invalid parameter.',
            ]);
        }
    }

}
