<?php

namespace App\Http\Livewire;

use App\User;
use App\UserDelegation;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class DelegationsTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    protected $search_fields = [
        'display_name',
        'name',
        'firstname',
        'lastname',
        'pea',
    ];

    public $search = '';

    public $per_page = 25;

    public $sort_field = 'created_at';

    public $sort_descending = true;

    public function sortBy($field)
    {
        $this->sort_descending = ($this->sort_field === $field) ? !$this->sort_descending : false;
        $this->sort_field = $field;
    }

    public function updating($name)
    {
        // reset pagination when searching or filtering
        if (in_array($name, ['search', 'per_page'])) {
            $this->resetPage();
        }
    }

    public function render()
    {
        $delegations_query = UserDelegation::query()
            ->search($this->search, $this->search_fields)
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
            });

        return view('livewire.delegations-table', [
            'delegations' => $delegations_query->paginate($this->per_page),
        ]);
    }
}
