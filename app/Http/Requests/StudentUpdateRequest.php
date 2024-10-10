<?php

namespace App\Http\Requests;

use App\StudentData;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class StudentUpdateRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'full_name' => 'required|string',
            'faculty.*' => [
                'integer',
                Rule::exists('profiles', 'id')->where('public', 1),
            ],
            'research_profile.major' => 'required',
            'research_profile.brief_intro' => [
                'required',
                'string',
                'max:280',
            ],
            'research_profile.intro' => [
                'required',
                'string',
                'max:250',
            ],
            'research_profile.interest' => [
                'required',
                'string',
                'max:200',
            ],
            'research_profile.semesters' => 'required|array|min:1',
            'research_profile.schools' => 'required|array|min:1',
            'research_profile.graduation_date' => 'required|date|after:today',
            'research_profile.credit' => 'required|numeric|between:-1,1',
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
            'faculty.*.exists' => 'The faculty member(s) selected could not be validated.',
            'faculty.required' => 'You must selest at least one faculty member that you would like to work with',
            'research_profile.major' => 'The major field is required',
            'research_profile.semesters.required' => 'At least one semester is required',
            'research_profile.schools.required' => 'At least one school is required',
            'research_profile.graduation_date.after' => 'The graduation date must be in the future',
            'research_profile.credit.between' => 'The credit value is invalid',
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
            'research_profile.semesters' => 'semesters',
            'research_profile.schools' => 'school',
            'research_profile.graduation_date' => 'graduation date',
            'research_profile.credit' => 'credit',
            'research_profile.brief_intro' => 'research opportunity reasons',
            'research_profile.intro' => 'future goals',
            'research_profile.interest' => 'interest',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $semesters = $this->input('research_profile.semesters', []);
            $availability = $this->input('research_profile.availability', []);
            $schools = $this->input('research_profile.schools', []);
            $custom_questions = StudentData::customQuestions();
            $school_custom_questions = $this->input('research_profile.school_custom_questions', []);
            $languages = $this->input('research_profile.languages', []);
            $lang_proficiency = $this->input('research_profile.lang_proficiency', []);
            $languages_attr = StudentData::$languages;
            $hours_available = [
                'hours' => 'per week', 
                'hours_weekdays' => 'per days', 
                'hours_weekends' => 'for weekends', 
                'hours_specific' => 'specific'
            ];

            foreach ($semesters as $semester) { // Validates the presence of the hours availability based on the semesters selection
                $semester_key = Str::lower(Str::replace(' ', '-', $semester));
                if (!isset($availability[$semester_key])) {
                    $validator->errors()->add('availability.' . $semester_key, "The availability for $semester is required.");
                } 
                else {
                    foreach ($hours_available as $hours => $frequency) {
                        if (is_null($availability[$semester_key][$hours])) {
                            $validator->errors()->add("availability.{$semester_key}.{$hours}", "The number of hours {$frequency} are required for $semester.");
                        }
                    }
                }
            }

            foreach ($languages as $language) { // Validates the presence of the language proficiency based on the language selection
                if (!array_key_exists($language, $lang_proficiency) || is_null($lang_proficiency[$language])) {
                    $validator->errors()->add("research_profile.lang_proficiency.{$language}", "The level of proficiency for $languages_attr[$language] is required.");
                }
            }

            foreach ($schools as $key => $school) { // Validates the answers to the custom questions based on the school selection
                if (!isset($school_custom_questions[$school])) {
                    $validator->errors()->add("research_profile.school_custom_questions.{$school}", "The specific questions for $school are required.");
                }
                foreach ($custom_questions[$school] as $question) {
                    if (!isset($school_custom_questions[$school][$question['name']])) {
                        $validator->errors()->add("research_profile.school_custom_questions.{$school}", "The specific questions for $school are required.");
                    }
                }
            }
        });
    }

}
