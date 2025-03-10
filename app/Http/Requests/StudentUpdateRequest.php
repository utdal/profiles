<?php

namespace App\Http\Requests;

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
                Rule::exists('profiles', 'id')->where('public', 1),
            ],
            'research_profile.major' => [
                'required',
                Rule::in($majors)
            ],
            'research_profile.brief_intro' => 'sometimes|string|max:280',
            'research_profile.intro' => 'sometimes|string|max:280',
            'research_profile.interest' => 'sometimes|string|max:280',
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
            'research_profile.*' => $this->validateCustomQuestions(),
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
            'faculty.required' => 'You must selest at least one faculty member that you would like to work with.',
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
            'research_profile.semesters' => 'semesters',
            'research_profile.schools' => 'school',
            'research_profile.graduation_date' => 'graduation date',
            'research_profile.credit' => 'credit',
            'research_profile.brief_intro' => 'research opportunity reasons',
            'research_profile.intro' => 'future goals',
            'research_profile.interest' => 'interest',
        ];
    }

    /**
     * Check if attr is a custom question and validates the value based on the question type.
     * Supported question types:
     * - `yes_no`: Validates that the value is either "1" or "0".
     * - Other types: Ensures the value does not exceed 1000 characters if it is a string.
     * 
     * @return \Closure A validation closure to be used in validation rules.
     */
    public function validateCustomQuestions()
    {
        return function($attr, $value, $fail) {
            
            $errors = [];
            
            $questions = StudentData::customQuestions()->flatten(1);
            $question_name = substr($attr, strpos($attr, '.') + 1);

            $question_settings = $questions->first(fn($q) => isset($q['name'], $q['type']) && $q['name'] === $question_name);

            if (is_null($question_settings)) {
                return false;
            }

            switch ($question_settings['type']) {
                case 'yes_no':
                    if (!in_array($value, ["1", "0"], true)) {
                        $formatted_question_name = $this->formatQuestionName($question_name);
                        $errors[] = "The answer to {$formatted_question_name} must be Yes or No.";
                    }
                    break;
                default:
                    if (is_string($value) && strlen($value) > 1000) {
                        $formatted_question_name = $this->formatQuestionName($question_name);
                        $errors[] = "The value for the {$formatted_question_name} question cannot exceed 1000 characters.";
                    }
                    break;
            }

            foreach ($errors as $error) {
                $fail($error);
            }
        };
    }

    /**
     * Helper function to format question name
     * @param string $question_name
     * @return string
    */ 
    function formatQuestionName($question_name) {
        return strtolower(str_replace('_', ' ', $question_name));
    }
}
