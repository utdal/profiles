<?php

namespace App\Http\Livewire\Concerns;

trait HasSorting
{
    public $sort_field = 'id';

    public $sort_descending = true;

    public function sortBy($field)
    {
        $this->sort_descending = ($this->sort_field === $field) ? !$this->sort_descending : false;
        $this->sort_field = $field;
    }
}
