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

    public function humanizeFilters(Collection $filters)
    {
        $parts = [];

        foreach ($filters as $key => $value) {
            if (!isset($this->filter_readable_values[$key])) {
                continue;
            }
        
            $config = $this->filter_readable_values[$key];
            $label = $config['label'] ?? '';
            $readable_value = $value;
        
            if (isset($label) && isset($config['values']) && array_key_exists($value, $config['values'])) {
                $readable_value = "{$label} {$config['values'][$value]}";
                $parts[] = $readable_value;
            }
            elseif (isset($config['values']) && array_key_exists($value, $config['values'])) {
                $readable_value = $config['values'][$value];
                $parts[] = $readable_value;
            }
            elseif ($key === 'graduation_filter') {
                $formatted = \Carbon\Carbon::parse($value)->format('F Y');
                $parts[] = "$label $formatted";
            }
            elseif ($label) {
                $parts[] = "$label $readable_value";
            } else {
                $parts[] = $readable_value;
            }
        }

        $summary = $this->formatFiltersWithCommasAnd($parts);

        return $summary;
    }

    protected function formatFiltersWithCommasAnd(Collection|array $items)
    {
        $items = collect($items)->filter(); // Remove null/empty
        $count = $items->count();

        if ($count === 0) {
            return '';
        } elseif ($count === 1) {
            return $items->first();
        } elseif ($count === 2) {
            return $items->implode(', ');
        } else {
            return $items->slice(0, -1)->implode(', ') . ', and ' . $items->last();
        }
    }

    public function getFilterReadableValuesProperty()
    {
        return [
            'search_filter' => ['label' => 'containing'],
            'semester_filter' => ['label' => 'for'],
            'tag_filter' => ['label' => 'containing'],
            'schools_filter' => ['label' => 'for'],
            'major_filter' => ['label' => 'majoring in'],
            'language_filter' => [
                'label' => 'speaking',
                'values'=> StudentData::$languages,
            ],
            'travel_filter' => [
                'values' => [
                    '0'  => 'not open to travel to research centers in Dallas',
                    '1'  => 'open to travel to research centers in Dallas',
                ],
            ],
            'travel_other_filter' => [
                'values' => [
                    '0'  => 'not open to travel regularly to sites in the Dallas area',
                    '1'  => 'open to travel regularly to sites in the Dallas area',
                ],
            ],
            'animals_filter' => [
                'values' => [
                    '0'  => 'not open to working with animals',
                    '1'  => 'open to working with animals',
                ],
            ],
            'credit_filter' => [
                'label' => 'for',
                'values' => [
                    '0'  => 'research volunteer',
                    '1'  => 'research credit',
                    '-1' => 'no preference on research credit',
                ],
            ],
            'graduation_filter' => ['label' => 'graduating in'],
        ];
    }
}
