<?php

namespace App\Http\Livewire;

use App\ProfileStudent;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class StudentFiler extends Component
{
    use AuthorizesRequests;

    public $student;

    public $profile;

    public $status = '';

    public function updateStatus(string $new_status, string $new_status_name): void
    {
        $this->authorize('update', [ProfileStudent::class, $this->profile]);
    
        $updated = $this->profile->students()->updateExistingPivot($this->student->id, [
            'status' => $new_status ?: null,
        ]);

        if ($updated) {
            $this->student->updateStatusStats($this->status, $new_status, $this->profile);
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
