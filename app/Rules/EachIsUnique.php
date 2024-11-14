<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;
use Spatie\Tags\Tag;

class EachIsUnique implements ValidationRule
{
    /**
     * All of the data under validation.
     *
     * @var array<string, mixed>
     */
    protected $data = [];
    protected string $delimiter;
    protected string $table;
    protected string $column;
    protected array $constraint;
    protected $ignore;

    /**
     * Create a new validation rule instance.
     *
     * @param string $delimiter   Delimiter to split the field under validation into individual elements.
     * @param string $table       Table where the uniqueness check will be performed.
     * @param string $column      Column in the table where uniqueness will be checked.
     * @param array  $constraint  Constraint for the where method to apply when checking uniqueness in the format of ['column', 'value'].
     * @param mixed  $ignore      Value to ignore in the uniqueness check if the request is an update (id by deafult). It can receive an array in the format of [value, p_key]
     *                            if the primary key is different than the 'id'.
     */
    public function __construct($delimiter, $table, $column, $constraint = null, $ignore = null)
    {
        $this->delimiter = $delimiter;
        $this->table = $table;
        $this->column = $column;
        $this->constraint = $constraint;
        $this->ignore = $ignore;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $values = $errors = [];

        $values = $this->split($value);

        $rule = Rule::unique($this->table, $this->column);

        if (isset($this->constraint)) {
            $rule->where($this->constraint[0], $this->constraint[1]); 
        }

        if (isset($this->ignore)) {
            if (is_array($this->ignore)) {
                if (isset($this->ignore[1])) { // If the array has two values (value to ignore and p_key column name if p_key is other than the id)
                    $rule->ignore($this->ignore[0], $this->ignore[1]); // Ignore the value for the specified column
                } else {
                    $rule->ignore($this->ignore[0]); // If there's only one value, ignore just the first (likely the ID)
                }
            } elseif (is_object($this->ignore) || is_string($this->ignore)) { // If ignore is a string or an object (likely the ID), ignore this value
                $rule->ignore($this->ignore);
            }
        }

        foreach ($values as $value) {

            $valid = !validator([$attribute => $value], [$attribute => $rule])->fails();

            if (!$valid) {
                $errors[] = "The {$value} tag already exists for the {$this->constraint[1]} {$this->constraint[0]}.";
            }
        }

        if (!empty($errors)) {
            foreach ($errors as $error) {
                $fail($error);
            }
        }
    }

    public function split($value)
    {
        if (is_string($value)) {
            return preg_split($this->delimiter, $value);
        }
    }

}
