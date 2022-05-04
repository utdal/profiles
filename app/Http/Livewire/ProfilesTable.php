<?php

namespace App\Http\Livewire;

use App\Http\Livewire\Concerns\HasFilters;
use App\Http\Livewire\Concerns\HasPagination;
use App\Http\Livewire\Concerns\HasSorting;
use App\Profile;
use App\School;
use Livewire\Component;

class ProfilesTable extends Component
{
    use HasFilters;
    use HasPagination;
    use HasSorting;

    protected $search_fields = [
        'id',
        'full_name',
        'first_name',
        'last_name',
        'slug',
    ];

    public $search_filter = '';

    public $public_filter = '';

    public $schools_filter = '';

    public function mount()
    {
        $this->sort_field = 'last_name';
        $this->sort_descending = false;
    }

    public function getProfilesProperty()
    {
        return Profile::query()
            ->with(['user.school', 'user.setting'])
            ->when($this->search_filter, function ($q) {
                $q->where(function ($search_q) {
                    foreach ($this->search_fields as $field) {
                        $search_q->orWhere($field, 'LIKE', "%{$this->search_filter}%");
                    }
                });
            })
            ->when($this->schools_filter !== '', function ($q) {
                $q->fromSchoolId($this->schools_filter);
            })
            ->when($this->public_filter !== '', function ($q) {
                $q->where('public', '=', $this->public_filter);
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
        return view('livewire.profiles-table', [
            'filter_names' => $this->availableFilters(),
            'schools' => School::all(),
        ]);
    }
}
