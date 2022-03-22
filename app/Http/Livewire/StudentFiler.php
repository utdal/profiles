<?php

namespace App\Http\Livewire;

use Livewire\Component;

class StudentFiler extends Component
{
    public $student;

    public $profile;

    public $status = null;

    public function mount()
    {
        $this->status = $this->profile->students()->find($this->student->id)->application->status;
    }

    public function updatedStatus($value)
    {
        $updated = $this->profile->students()->updateExistingPivot($this->student->id, [
            'status' => $value ?: null,
        ]);

        if ($updated) {
            $this->emit('alert', "Student filed.", 'success');
            $this->emit('profileStudentStatusUpdated');
        } else {
            $this->emit('alert', "Unable to file student.", 'danger');
        }
    }

    public function render()
    {
        return view('livewire.student-filer');
    }
}
