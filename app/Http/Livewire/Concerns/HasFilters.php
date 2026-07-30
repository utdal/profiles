<?php

namespace App\Http\Livewire\Concerns;

trait HasFilters
{
    public function resetFilters()
    {
        $this->reset($this->availableFilters());
        $this->dispatch('alert', message: "Cleared all filters.", type: 'success');
    }

    public function resetFilter($filter_name)
    {
        $this->reset($filter_name);
        $this->updated($filter_name, '');
    }

    protected function emitFilterUpdatedEvent($name, $value)
    {
        if ($this->isAFilter($name)) {
            $this->dispatch('alert', message: ($value === '') ? "Cleared filter." : "Applied filter.", type: 'success');
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
