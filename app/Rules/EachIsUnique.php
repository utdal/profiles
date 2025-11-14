<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class EachIsUnique implements ValidationRule
{
    /** Delimiter to split the field under validation into individual elements. */
    protected string $delimiter;

    /** Table where the uniqueness check will be performed. */
    protected string $table;

    /** Column in the table where uniqueness will be checked. */
    protected string $column;

    /** Constraint for the where method to apply when checking uniqueness in the format of ['column', 'value']. */
    protected array|null $constraint;

    /** The ID or model instance of the record to ignore. */
    protected mixed $ignore_id;

    /** The column name of the primary key, if it's other than the ID. */
    protected string|null $ignore_column;


    /**
     * Create a new validation rule instance.
     */
    public function __construct(string $delimiter, string $table, string $column, ?array $constraint = null)
    {
        $this->delimiter = $delimiter;
        $this->table = $table;
        $this->column = $column;
        $this->constraint = $constraint;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $values = $errors = [];

        $values = is_string($value) ? preg_split($this->delimiter, $value) : [];

        $rule = Rule::unique($this->table, $this->column);

        if (isset($this->constraint)) {
            $rule->where($this->constraint[0], $this->constraint[1]); 
        }

        if (isset($this->ignore_id)) {
            $rule->ignore($this->ignore_id, $this->ignore_column);
        }

        foreach ($values as $value) {
            $valid = !validator([$attribute => $value], [$attribute => $rule])->fails();

            if (!$valid) {
                $errors[] = "The {$value} tag already exists for the {$this->constraint[1]} {$this->constraint[0]}.";
            }
        }

        foreach ($errors as $error) {
            $fail($error);
        }
    }

    /**
     * Set values to be excluded or ignored by the unique checks.
     */
    public function ignore(mixed $id, ?string $idColumn = null)
    {
        $this->ignore_id = $id;
        $this->ignore_column = $idColumn;

        return $this;
    }

}
