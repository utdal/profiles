<?php

namespace App\Http\Requests;

use App\Rules\EachIsUnique;
use App\Student;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TagUpdateRequest extends FormRequest
{
    public array $tag_types = [];

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $locale = app()->getLocale();
        
        $this->tag_types += [
            "App\\Profile",
            ...Student::participatingSchools()->keys()->map(fn($shortname) => "App\\Student\\{$shortname}"),
        ];

        return [
            'type' => [
                'required',
                'string',
                Rule::in($this->tag_types),
            ],
            'name' => [
                'required',
                'string',
                'max:100',
                (new EachIsUnique('/\r\n|\r|\n/', 'tags', 'name->'.$locale, ['type', $this->input('type')]))
                    ->ignore($this->route()->parameters['tag'] ?? null),
            ],
        ];
    }

    public function messages(): array
    {
        $types_allowed = implode(', ', $this->tag_types);

        return [
            'type.in' => "The tag types allowed are: {$types_allowed}",
        ]; 
    }

    /**
     * Get custom attribute names for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'tag name',
            'type' => 'tag type',
        ];
    }

}
