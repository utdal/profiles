<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\HasImageUploads;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    use HasImageUploads;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $common_rules = [
            'data.*.image' => $this->uploadedImageRules(),
        ];

        if ($this->route()->parameter('section') !== 'information') {
            $common_rules = array_merge($common_rules, $this->dataPresenceRules());
        }
        
        return array_merge($common_rules, $this->sectionRules());
    }

    public function sectionRules(): array
    {
        $rulesMethod = $this->route()->parameter('section') . 'Rules';

        return method_exists($this, $rulesMethod) ? $this->$rulesMethod() : [];
    }

    public function informationRules(): array
    {
        return [
            'full_name' => 'required|string',
            'public' => 'required|boolean',
            'type' => [
                Rule::prohibitedIf(fn () => $this->user()->cannot('updateAdvanced', $this->route('profile'))),
                'numeric',
                'in:0,1,2',
            ],
            'data.*.data.title' => 'required|string',
            'data.*.data.email' => 'nullable|email',
            'data.*.data.profile_summary' => 'nullable|between:1,280',
            'data.*.data.url' => 'nullable|url',
            'data.*.data.secondary_url' => 'nullable|url',
            'data.*.data.tertiary_url' => 'nullable|url',
            'data.*.data.orc_id' => 'nullable|required_if_accepted:data.*.data.orc_id_managed|regex:/^[0-9]{4}-[0-9]{4}-[0-9]{4}-[0-9]{3}[0-9X]$/',
            'data.*.data.orc_id_managed' => 'required|boolean',
            'data.*.data.fancy_header' => 'required|boolean',
            'data.*.data.fancy_header_right' => 'required|boolean',
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
            'data.*.image.max' => $this->uploadedImageMessages('max'),
            'data.*.id.exists' => 'Some of the profile entries you are attempting to update might have been modified since you loaded this page. Please reload this page and try again.',
            'data.*.data.orc_id.required_if_accepted' => 'The ORCID field is required when Auto-update Publications is enabled.',
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
            'full_name' => 'Display Name',
            'data.*.data.title' => 'Title',
            'data.*.data.email' => 'Email address',
            'data.*.data.url' => 'Primary URL',
            'data.*.data.secondary_url' => 'Secondary URL',
            'data.*.data.tertiary_url' => 'Tertiary URL',
            'data.*.data.orc_id' => 'ORCID',
        ];
    }

    public function dataPresenceRules(): array
    {
        return [
            'data.*.id' => 
                Rule::forEach(function ($value, $attribute) {
                    if ($value > 0) {
                        return [
                            Rule::exists('profile_data', 'id')->where(function($query) {
                                return $query->where([
                                    'profile_id' => $this->route()->parameters['profile']->id
                                ]);
                            }),
                        ];
                    }
                    return [];
                })
            ];
    }
}
