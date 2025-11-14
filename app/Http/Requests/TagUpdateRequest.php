<?php

namespace App\Http\Requests;

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
            ...Student::participatingSchools()->keys()->map(fn($shortname) => "App\\Student\\{$shortname}")->all(),
        ];

        $tag_field = $this->hasMultipleTags() ? 'name.*' : 'name';

        return [
            'type' => [
                'required',
                'string',
                Rule::in($this->tag_types),
            ],
            $tag_field => [
                'required',
                'string',
                'max:100',
                Rule::unique('tags', 'name->' . $locale)
                    ->where('type', $this->input('type'))
                    ->ignore($this->route()->parameters['tag'] ?? null),
            ],
        ];
    }

    protected function prepareForValidation(): void
    {
        // split string of multiple tag names into an array for easier validation
        if ($this->hasMultipleTags()) {
            $this->merge([
                'name' => preg_split('/\r\n|\r|\n/', $this->name ?? ''),
            ]);
        }
    }

    protected function hasMultipleTags(): bool
    {
        return $this->routeIs('tags.store');
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
