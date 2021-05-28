<?php

namespace App\Http\Livewire;

use App\Setting;
use App\School;
use App\User;
use Livewire\Component;
use Livewire\WithPagination;

class UsersTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    protected $search_fields = [
        'id',
        'display_name',
        'name',
        'firstname',
        'lastname',
        'pea',
    ];

    public $search = '';

    public $title_filter = '';

    public $schools_filter = '';

    public $department_filter = '';

    public $per_page = 25;

    public $sort_field = 'lastname';

    public $sort_descending = false;

    public function sortBy($field)
    {
        $this->sort_descending = ($this->sort_field === $field) ? !$this->sort_descending : false;
        $this->sort_field = $field;
    }

    public function updating($name)
    {
        // reset pagination when searching or filtering
        if (in_array($name, ['search', 'title_filter', 'schools_filter', 'department_filter', 'per_page'])) {
            $this->resetPage();
        }
    }

    public function render()
    {
        $users_query = User::query()
            ->with(['school', 'profiles', 'setting'])
            ->when($this->search, function ($q) {
                $q->where(function ($search_q) {
                    foreach ($this->search_fields as $field) {
                        $search_q->orWhere($field, 'LIKE', "%{$this->search}%");
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
            ->orderBy($this->sort_field, $this->sort_descending ? 'desc' : 'asc');

        return view('livewire.users-table', [
            'users' => $users_query->paginate($this->per_page),
            'schools' => School::all(),
            'titles' => User::pluck('title')->unique()->filter()->sort(),
            'departments' => User::pluck('department')->unique()->filter()->sort(),
            // 'username' => optional(Setting::whereName('account_name')->first())->value ?? 'Username',
        ]);
    }
}
