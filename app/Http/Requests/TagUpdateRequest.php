<?php

namespace App\Http\Requests;

use App\Rules\TagNameUniqueness;
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
        
        foreach (Student::participatingSchools() as $shortname => $name) {
            $this->tag_types[]= "App\\Student\\{$shortname}";
        }

        return [
            'type' => [
                        'required',
                        Rule::in($this->tag_types),
                    ],
            'name' => [
                        'string',
                        new TagNameUniqueness,
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

}
