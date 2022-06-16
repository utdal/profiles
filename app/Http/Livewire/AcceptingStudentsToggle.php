<?php

namespace App\Http\Livewire;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class AcceptingStudentsToggle extends Component
{
    use AuthorizesRequests;

    /** @var \App\Profile */
    public $profile;

    /** @var bool If the profile is not accepting students */
    public $not_accepting_students;

    public function mount()
    {
        $this->not_accepting_students = (bool)$this->profile->information()->first()->not_accepting_students;
    }

    public function updatedNotAcceptingStudents($toggled_on)
    {
        $this->authorize('update', $this->profile);

        /** @var \App\ProfileData fresh copy of the profile info */
        $info = $this->profile->information()->first();

        $updated = $info->updateData(['not_accepting_students' => $toggled_on ? '1' : '0']);

        if (!$updated) {
            $this->emit('alert', "Not saved. There was a problem changing that setting", 'danger');
        }

        $this->emit('alert', "<strong>Saved.</strong><br> Profile for {$this->profile->full_name} marked as " . ($toggled_on ? "not" : "") . " accepting students", 'success');
    }

    public function render()
    {
        return view('livewire.accepting-students-toggle');
    }
}
