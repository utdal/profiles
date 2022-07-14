<?php

namespace App\Http\Livewire;

use App\Profile;
use Livewire\Component;
use Livewire\WithPagination;

class PaginatedData extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $data_type;

    public function mount(Profile $profile, $editable, $data_type, $per_page)
    {
        $this->profile = $profile;
        $this->editable = $editable;
        $this->data_type = $data_type;
        $this->per_page = $per_page;
    }

    public function data()
    {
        return match($this->data_type)
        {
            'publications' => $this->profile->data()->publications()->paginate($this->per_page, ['*'], $this->data_type),
            'appointments' => $this->profile->data()->appointments()->paginate($this->per_page, ['*'], $this->data_type),
            'awards' => $this->profile->data()->awards()->paginate($this->per_page, ['*'], $this->data_type),
            'news' => $this->profile->data()->news()->paginate($this->per_page, ['*'], $this->data_type),
            'support' => $this->profile->data()->support()->paginate($this->per_page, ['*'], $this->data_type),
            'presentations' => $this->profile->data()->presentations()->paginate($this->per_page, ['*'], $this->data_type),
            'projects' => $this->profile->data()->projects()->paginate($this->per_page, ['*'], $this->data_type),
            'additionals' => $this->profile->data()->additionals()->paginate($this->per_page, ['*'], $this->data_type),
            'affiliations' => $this->profile->data()->affiliations()->paginate($this->per_page, ['*'], $this->data_type),
        };
    }
    
    public function render()
    {
        return view('livewire.profile-data/'.$this->data_type, [
            'data' => $this->data(),
            'editable' => $this->editable,
            'profile' => $this->profile,
        ]);
    }
}