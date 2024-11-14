<?php

namespace App\Http\Requests;

use App\Rules\EachIsUnique;
use App\Student;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TagUpdateRequest extends FormRequest
{
    public $tag_types = [];

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
     * @return array
     */
    public function rules()
    {
        $this->tag_types[] = "App\\Profile";
        $locale = app()->getLocale();
        
        foreach (Student::participatingSchools() as $shortname => $name) {
            $this->tag_types[]= "App\\Student\\{$shortname}";
        }

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
                        'ignore_case',
                        new EachIsUnique('/\r\n|\r|\n/', 'tags', 'name->'.$locale, ['type', $this->input('type')], $this->route()->parameters['tag']),
                    ],
        ];
    }

    public function messages()
    {
        $types_allowed = implode(', ', $this->tag_types);

        return [
            'type.in' => "The tag types allowed are: {$types_allowed}",
        ]; 
    }


    public function split($value)
    {
        if (is_string($value)) {
            return preg_split($this->delimeter, $value);
        }
    }

}
