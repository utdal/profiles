<?php

namespace App\Http\Livewire;

use Illuminate\Support\Str;
use Livewire\Component;

class ProfileStudents extends Component
{
    public $profile;

    public $students = [];

    protected $listeners = [
        'profileStudentStatusUpdated' => 'refreshLists'
    ];

    public function mount()
    {
        $this->refreshLists();
    }

    public function refreshLists()
    {
        $this->students = $this->profile->students()->submitted()->with('user:id,email')->get();
    }

    public function render()
    {
        return view('livewire.profile-students');
    }
}
