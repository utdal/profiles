<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;
use Spatie\Tags\Tag;

class EachIsUnique implements ValidationRule, DataAwareRule
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


    public function __construct($delimiter, $table, $column, $constraint, $ignore)
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

        foreach ($values as $value) {

            $rule = Rule::unique($this->table, $this->column)
                            ->where($this->constraint[0], $this->constraint[1])
                            ->ignore($this->ignore);

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
