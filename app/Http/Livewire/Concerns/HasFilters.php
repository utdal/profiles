<?php

namespace App\Http\Livewire\Concerns;

use App\StudentData;
use Illuminate\Support\Collection;

trait HasFilters
{
    public function resetFilters()
    {
        $this->reset($this->availableFilters());
        $this->emit('alert', "Cleared all filters.", 'success');
    }

    public function resetFilter($filter_name)
    {
        $this->reset($filter_name);
        $this->updated($filter_name, '');
    }

    protected function emitFilterUpdatedEvent($name, $value)
    {
        if ($this->isAFilter($name)) {
            $this->emit('alert', ($value === '') ? "Cleared filter." : "Applied filter.", 'success');
        }
    }

    protected function availableFilters(): array
    {
        return array_filter(array_keys(get_class_vars(self::class)), function ($property_name) {
            return $this->isAFilter($property_name);
        });
    }

    protected function isAFilter($name)
    {
        // Any property with a name including "_filter"
        return strpos($name, '_filter') !== false;
    }

}
