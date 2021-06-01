<?php

namespace App\Http\Livewire;

use App\Profile;
use App\School;
use Livewire\Component;
use Livewire\WithPagination;

class ProfilesTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    protected $search_fields = [
        'id',
        'full_name',
        'first_name',
        'last_name',
        'slug',
    ];

    public $search = '';

    public $public_filter = '';

    public $schools_filter = '';

    public $per_page = 25;

    public $sort_field = 'last_name';

    public $sort_descending = false;

    public function sortBy($field)
    {
        $this->sort_descending = ($this->sort_field === $field) ? !$this->sort_descending : false;
        $this->sort_field = $field;
    }

    public function updating($name)
    {
        // reset pagination when searching or filtering
        if (in_array($name, ['search', 'public_filter', 'per_page'])) {
            $this->resetPage();
        }
    }

    public function render()
    {
        $profiles_query = Profile::query()
            ->with(['user.school', 'user.setting'])
            ->when($this->search, function ($q) {
                $q->where(function ($search_q) {
                    foreach ($this->search_fields as $field) {
                        $search_q->orWhere($field, 'LIKE', "%{$this->search}%");
                    }
                });
            })
            ->when($this->schools_filter !== '', function ($q) {
                $q->fromSchoolId($this->schools_filter);
            })
            ->when($this->public_filter !== '', function ($q) {
                $q->where('public', '=', $this->public_filter);
            })
            ->orderBy($this->sort_field, $this->sort_descending ? 'desc' : 'asc');

        return view('livewire.profiles-table', [
            'profiles' => $profiles_query->paginate($this->per_page),
            'schools' => School::all(),
        ]);
    }
}
