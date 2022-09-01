<?php

namespace App\Http\Livewire;

use App\Profile;
use Livewire\Component;
use Livewire\WithPagination;

class ProfileDataCard extends Component
{
    use WithPagination;

    const PER_PAGE_FOR_SECTION = [
        'default' => 5,
        'publications' => 8,
        'appointments' => 10,
        'awards' => 10,
        'news' => 5,
        'support' => 5,
        'presentations' => 5,
        'projects' => 5,
        'additionals' => 3,
        'affiliations' => 10
    ];

    protected $paginationTheme = 'bootstrap';
    public $profile;
    public $data_type;
    public $editable;
    public $paginated = true;
    public $per_page;
    public $public_filtered = false;

    public function mount(Profile $profile, $editable, $data_type, $paginated = true, $public_filtered = false)
    {
        $this->profile = $profile;
        $this->editable = $editable;
        $this->data_type = $data_type;
        $this->paginated = $paginated;
        $this->public_filtered = $public_filtered;
    }

    public function data()
    {
        $data_query = $this->profile->{$this->data_type}();

        if ($this->public_filtered) {
            $data_query = $data_query->public();
        }

        if ($this->paginated) {
            return $data_query->paginate(
                $this::PER_PAGE_FOR_SECTION[$this->data_type] ?? $this::PER_PAGE_FOR_SECTION['default'],
                ['*'],
                $this->data_type
            );
        }

        return $data_query->get();
    }

    public function render()
    {
        $data = $this->data();

        if ($data->isEmpty() && !$this->editable) {
            return '';
        }

        return view("livewire.profile-data-cards/{$this->data_type}", [
            'data' => $data,
            'editable' => $this->editable,
            'profile' => $this->profile,
            'paginated' => $this->paginated,
        ]);
    }
}