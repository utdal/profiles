<?php

namespace App\Http\Livewire\Concerns;

use Livewire\WithPagination;

trait HasPagination
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $per_page = 25;

    protected function resetPageOnChange($name)
    {
        // reset pagination when searching or filtering
        if (
            $name === 'per_page'
            || (method_exists($this, 'isAFilter') && $this->isAFilter($name))
        ) {
            $this->resetPage();
        }
    }
}
