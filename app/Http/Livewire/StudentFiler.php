<?php

namespace App\Http\Livewire;

use App\ProfileStudent;
use Livewire\Component;

class StudentFiler extends Component
{
    public $student;

    public $profile;

    public $status = '';

    public function updateStatus(string $new_status, string $new_status_name): void
    {
        $updated = $this->profile->students()->updateExistingPivot($this->student->id, [
            'status' => $new_status ?: null,
        ]);

        if ($updated) {
            $this->status = $new_status;
            $this->emit('alert', "{$this->student->full_name} filed as {$new_status_name}", 'success');
            $this->emit('profileStudentStatusUpdated');
        } else {
            $this->emit('alert', "Unable to file student.", 'danger');
        }
    }

    public function render()
    {
        return view('livewire.student-filer', [
            'statuses' => ProfileStudent::$statuses,
            'status_icons' => ProfileStudent::$icons,
        ]);
    }
}
