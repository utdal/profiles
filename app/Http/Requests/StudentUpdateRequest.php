<?php

namespace App\Http\Requests;

use App\Enums\ProfileType;
use App\Student;
use App\StudentData;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;

class StudentUpdateRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $schools = Student::participatingSchools()->keys()->all();
        $majors = StudentData::majors()->merge(['Other' => 'Other'])->all();

        return [
            'full_name' => 'required|string',
            'faculty.*' => [
                'integer',
                Rule::exists('profiles', 'id')
                    ->where('public', 1)
                    ->whereNotIn('type', [
                        ProfileType::Unlisted->value,
                        ProfileType::InMemoriam->value,
                    ]),
            ],
            'research_profile.major' => [
                'sometimes',
                'nullable',
                Rule::in($majors)
            ],
            'research_profile.brief_intro' => 'sometimes|string',
            'research_profile.intro' => 'sometimes|string',
            'research_profile.interest' => 'sometimes|string',
            'research_profile.schools' => 'required|array',
            'research_profile.schools.*' => [
                'sometimes',
                'string',
                Rule::in($schools),
            ],
            'research_profile.availability' => 'sometimes|array',
            'research_profile.semesters' => 'required|array',
            'research_profile.semesters.*' => [
                'required',
                'string',
                'regex:/\b(Winter|Spring|Summer|Fall)\s\d{4}\b/',
            ],
            'research_profile.languages' => 'sometimes|array',
            'research_profile.languages.*' => [
                Rule::in(array_keys(StudentData::$languages)),
            ],
            'research_profile.lang_proficiency' => 'sometimes|array',
            'research_profile.lang_proficiency.*' => 'string|in:limited,basic,professional,native',
            'research_profile.graduation_date' => 'required|date|after:today',
            'research_profile.credit' => 'required|numeric|in:-1,0,1',
        ] + $this->customQuestionRules();
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'faculty.*.exists' => 'One or more of your selected faculty members are not available. Please review and update your selections.',
            'faculty.required' => 'You must select at least one faculty member that you would like to work with.',
            'research_profile.major.required' => 'The major field is required.',
            'research_profile.semesters.required' => 'At least one semester is required.',
            'research_profile.schools.required' => 'At least one school is required.',
            'research_profile.schools.*.in' => 'Wrong value :input. Please select a valid school.',
            'research_profile.semesters.*.regex' => 'Wrong value selected for semester. The semester must follow the "Season YYYY" format.',
            'research_profile.languages.*.in' => 'Wrong value :input selected for language. Please select a valid language.',
            'research_profile.graduation_date.after' => 'The graduation date must be in the future.',
            'research_profile.credit.between' => 'The credit value is invalid.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'full_name' => 'display name',
            'research_profile.major' => 'major',
            'research_profile.semesters' => 'semesters',
            'research_profile.schools' => 'school',
            'research_profile.graduation_date' => 'graduation date',
            'research_profile.credit' => 'credit',
            'research_profile.brief_intro' => 'research opportunity reasons',
            'research_profile.intro' => 'future goals',
            'research_profile.interest' => 'interest',
        ] + $this->customQuestionAttributes();
    }

    public function customQuestions(): Collection
    {
        return StudentData::customQuestions()->flatten(1);
    }

    public function customQuestionRules(): array
    {
        return $this->customQuestions()->mapWithKeys(function($question, $key) {
            return [
                "research_profile.{$question['name']}" => match ($question['type']) {
                    'yes_no' => 'sometimes|boolean',
                    'text' => 'sometimes|nullable|string|max:256',
                    'textarea' => 'sometimes|nullable|string',
                    default => 'sometimes|nullable',
                }
            ];
        })->all();
    }

    public function customQuestionAttributes(): array
    {
        return $this->customQuestions()->mapWithKeys(function($question, $key) {
            return [
                "research_profile.{$question['name']}" => "{$question['school']} research " . str_replace('_', ' ', $question['name'])
            ];
        })->all();
    }

}
