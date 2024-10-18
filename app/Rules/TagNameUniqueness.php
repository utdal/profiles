<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Route;
use Spatie\Tags\Tag;

class TagNameUniqueness implements ValidationRule, DataAwareRule
{
    /**
     * All of the data under validation.
     *
     * @var array<string, mixed>
     */
    protected $data = [];

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $locale = app()->getLocale();
        $errors = [];

        if (is_string($value)) {
            $value = preg_split('/\r\n|\r|\n/', $value);
        }

        foreach ($value as $tag_entry) {

            if (strlen($tag_entry) > 100) {
                $errors[] = 'The maximum characters allowed for a tag is 100. Please correct the :input entry.';
            }

            $current_tag = Route::current()->parameter('tag');

            $uniqueness_query = Tag::whereRaw("LOWER(JSON_UNQUOTE(name->'$.{$locale}')) = ?", [strtolower($tag_entry)])
                                    ->where('type', $this->data['type']);

            if ($current_tag) {
                $uniqueness_query->where('id', '!=', $current_tag->id);
            }

            $exists = $uniqueness_query->exists();

            if ($exists) {
                $errors[] = "The {$tag_entry} tag already exists for the {$this->data['type']} type.";
            }
        }

        if (!empty($errors)) {
            foreach ($errors as $error) {
                $fail($error);
            }
        }

    }

    /**
     * Set the data under validation.
     *
     * @param  array<string, mixed>  $data
     */
    public function setData(array $data): static
    {
        $this->data = $data;
 
        return $this;
    }
}
