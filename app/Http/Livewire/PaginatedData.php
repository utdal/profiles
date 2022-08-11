<?php

namespace App\Http\Livewire;

use App\Profile;
use Livewire\Component;
use Livewire\WithPagination;

class PaginatedData extends Component
{
    use WithPagination;

    const SECTIONS = [
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
    public $data_type;
    public $per_page;

    public function mount(Profile $profile, $editable, $data_type)
    {
        $this->profile = $profile;
        $this->editable = $editable;
        $this->data_type = $data_type;
    }

    public function data()
    {
        $section = $this->data_type;
        $per_page = $this::SECTIONS[$section];
        
        return $this->profile->$section()->paginate($per_page, ['*'], $section);
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