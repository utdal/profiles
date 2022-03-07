<?php

namespace App\Http\Livewire\Concerns;

trait ConvertEmptyStringsToNull
{
    /**
     * @var string[]
     */
    protected $convertEmptyStringsExcept = [
        //
    ];

    /**
     * @param string $name
     * @param mixed $value
     */
    public function updatedConvertEmptyStringsToNull(string $name, $value): void
    {
        if (!is_string($value) || in_array($name, $this->convertEmptyStringsExcept)) {
            return;
        }

        $value = $value === '' ? null : $value;

        data_set($this, $name, $value);
    }
}