<?php

namespace App\Http\Livewire;

use App\Http\Livewire\Concerns\HasFilters;
use App\Http\Livewire\Concerns\HasPagination;
use App\Http\Livewire\Concerns\HasSorting;
use App\UserDelegation;
use Illuminate\Support\Str;
use Livewire\Component;

class DelegationsTable extends Component
{
    use HasFilters;
    use HasPagination;
    use HasSorting;

    protected $search_fields = [
        'display_name',
        'name',
        'firstname',
        'lastname',
        'pea',
    ];

    public $search_filter = '';

    public $notify_filter = '';

    public function mount()
    {
        $this->sort_field = 'created_at';
        $this->sort_descending = true;
    }

    public function getDelegationsProperty()
    {
        return UserDelegation::query()
            ->search($this->search_filter, $this->search_fields)
            ->shouldNotify($this->notify_filter)
            ->when($this->sort_field, function ($q) {
                $sort_by = $this->sort_field;

                if (Str::startsWith($this->sort_field, 'delegator.')) {
                    $sort_by = function ($query) {
                        $query->select(Str::after($this->sort_field, 'delegator.'))
                            ->from('users')
                            ->whereColumn('id', 'user_delegations.delegator_user_id')
                            ->limit(1);
                    };
                } elseif (Str::startsWith($this->sort_field, 'delegate.')) {
                    $sort_by = function ($query) {
                        $query->select(Str::after($this->sort_field, 'delegate.'))
                            ->from('users')
                            ->whereColumn('id', 'user_delegations.delegate_user_id')
                            ->limit(1);
                    };
                }

                $q->orderBy($sort_by, $this->sort_descending ? 'desc' : 'asc');
            })
            ->paginate($this->per_page);
    }

    public function updating($name)
    {
        $this->resetPageOnChange($name);
    }

    public function updated($name, $value)
    {
        $this->emitFilterUpdatedEvent($name, $value);
    }

    public function render()
    {
        return view('livewire.delegations-table', [
            'filter_names' => $this->availableFilters(),
        ]);
    }
}
