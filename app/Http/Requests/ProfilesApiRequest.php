<?php

namespace App\Http\Requests;

use App\Rules\AllowedProfileDataType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class ProfilesApiRequest extends FormRequest
{
    /**
     * Letters, numbers, periods, dashes, semicolons
     * and non-consecutive apostrophes that are preceded and followed by a letter
     */
    public string $profile_slug_pattern = "/^([\p{L}\p{N}.;-]|(?<=[\p{L}])'(?!')(?=[\p{L}]))+$/u";

    /** 
     * Letters, marks, numbers, spaces, commas, periods, dashes, slash, parentheses
     * and non-consecutive apostrophes that are preceded and followed by a letter 
     */
    public string $search_pattern = "/^([\p{L}\p{M}\p{N}\p{Zs},\.\/\(\)-]|(?<=[\p{L}])'(?!')(?=[\p{L}]))+$/u";

    /** 
     * Letters, marks, numbers, spaces, commas, periods, dashes, slash, ampersand, parentheses, semicolons
     * and non-consecutive apostrophes that are preceded and followed by a letter 
     */
    public string $tag_pattern = "/^([\p{L}\p{M}\p{N}\p{Zs},\.\/&\(\);-]|(?<=[\p{L}])'(?!')(?=[\p{L}]))+$/u";

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
            'person' => ['sometimes', 'string', "regex:$this->profile_slug_pattern", 'min:3'],
            'search' => ['sometimes', 'string', "regex:$this->search_pattern", 'min:3', 'max:150'],
            'search_section' => ['sometimes', 'string', new AllowedProfileDataType()],
            'search_names' => ['sometimes', 'string', "regex:$this->search_pattern", 'min:3', 'max:150'],
            'info_contains' => ['sometimes', 'string', "regex:$this->search_pattern", 'min:3', 'max:150'],
            'from_school' => ['sometimes', 'string', 'regex:/^[a-zA-Z0-9\s;,\.]+$/'],
            'tag' => ['sometimes', 'string', "regex:$this->tag_pattern", 'min:3', 'max:150'],
            'accepting_undergrad' => 'sometimes|in:true,false,yes,no,on,off,1,0',
            'public' => 'sometimes|boolean',
            'with_data' => [
                'sometimes',
                'boolean',
                Rule::prohibitedIf(
                    $this->boolean('with_data')
                    && !$this->hasAny(['person', 'data_type'])
                ),
            ],
            'raw_data' => 'sometimes|boolean',
            'data_type' => [
                'sometimes',
                'filled',
                new AllowedProfileDataType(),
            ],
        ];
    }

    public function checkInvalidParameters(Validator $validator): void
    {
        $allowedKeys = array_keys($this->rules());

        $extraKeys = collect($this->all())
            ->keys()
            ->diff($allowedKeys);

        if ($extraKeys->isNotEmpty()) {
            $validator->errors()->add('extra_parameters', 'Invalid extra parameter(s) included.');
        }
    }

    public function after(): array
    {
        return [
            $this->checkInvalidParameters(...),
        ];
    }

    public function messages(): array
    {
        return [
            'with_data.prohibited' => ':attribute is prohibited without additional filtering.',
        ];
    }
}
