<?php

namespace App\Http\Livewire;

use App\Http\Livewire\Concerns\HasFilters;
use App\Http\Livewire\Concerns\HasPagination;
use App\Http\Livewire\Concerns\HasSorting;
use App\School;
use App\User;
use Livewire\Component;

class UsersTable extends Component
{
    use HasFilters;
    use HasPagination;
    use HasSorting;

    protected $search_fields = [
        'id',
        'display_name',
        'name',
        'firstname',
        'lastname',
        'pea',
    ];

    public $search_filter = '';

    public $title_filter = '';

    public $schools_filter = '';

    public $department_filter = '';

    public function mount()
    {
        $this->sort_field = 'lastname';
        $this->sort_descending = false;
    }

    public function getUsersProperty()
    {
        return User::query()
            ->with(['school', 'profiles', 'setting'])
            ->when($this->search_filter, function ($q) {
                $q->where(function ($search_q) {
                    foreach ($this->search_fields as $field) {
                        $search_q->orWhere($field, 'LIKE', "%{$this->search_filter}%");
                    }
                });
            })
            ->when($this->title_filter, function ($q) {
                $q->where('title', '=', $this->title_filter);
            })
            ->when($this->schools_filter, function ($q) {
                $q->withSchool($this->schools_filter);
            })
            ->when($this->department_filter, function ($q) {
                $q->where('department', '=', $this->department_filter);
            })
            ->orderBy($this->sort_field, $this->sort_descending ? 'desc' : 'asc')
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
        return view('livewire.users-table', [
            'filter_names' => $this->availableFilters(),
            'schools' => School::all(),
            'titles' => User::pluck('title')->unique()->filter()->sort(),
            'departments' => User::pluck('department')->unique()->filter()->sort(),
        ]);
    }
}
