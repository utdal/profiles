<?php

namespace App\Http\Requests;

use App\Student;
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
        $schools = Student::participatingSchools()->keys()->all();

        return [
            'full_name' => 'required|string',
            'faculty.*' => [
                'integer',
                Rule::exists('profiles', 'id')->where('public', 1),
            ],
            'research_profile.major' => [
                'required',
                Rule::in(StudentData::majors())
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
            'research_profile.*' => $this->validateCustomQuestions($schools),
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

    public function validateCustomQuestions($schools) 
    {
        return function($attr, $value, $fail) use ($schools) {
            
            $questions = StudentData::customQuestions()->flatten(1);
            $school_key = substr($attr, strpos($attr, '.') + 1);

            if (!in_array($school_key, $schools)) {
                return;
            }

            foreach ($value as $question => $answer) {
                
                $settings_question = $questions->first(function ($q) use ($question, $school_key) {
                    return isset($q['name'], $q['school'], $q['type']) && $q['name'] === $question && $q['school'] === $school_key;
                });

                if (!$settings_question) {
                    $fail("Question '{$question}' for school '{$school_key}' is not valid.");
                    continue;
                }

                switch ($settings_question['type']) {
                    case 'yes_no':
                        if (!in_array($answer, ["1", "0"])) {
                            $formated_question_name = strtolower(str_replace('_', ' ', $question));
                            $fail("The answer to {$formated_question_name} for school {$school_key} must be Yes or No.");
                        }
                        break;
                    default:
                    if (strlen($answer) > 1000) {
                        $formated_question_name = strtolower(str_replace('_', ' ', $question));
                        $fail("The value for {$formated_question_name} in school {$school_key} question cannot exceed 1000 characters.");
                    }
                    break;
                }
            }
        };
    }
}
