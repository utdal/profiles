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

        $old_status = $this->status;
    
        $updated = $this->profile->students()->updateExistingPivot($this->student->id, [
            'status' => $new_status ?: null,
        ]);

        if ($updated) {
            $this->student->updateStatusStats($old_status, $new_status, $this->profile);
            if ($new_status === 'accepted') {
                $this->student->updateAcceptedStats($this->profile);
            } elseif ($old_status === 'accepted') {
                $this->student->updateAcceptedStats($this->profile, false);
            }
            $this->status = $new_status;
            $this->dispatch('alert', message: "{$this->student->full_name} filed as {$new_status_name}",  type: 'success');
            $this->dispatch('profileStudentStatusUpdated');
        } else {
            $this->dispatch('alert', message: "Unable to file student.", type: 'danger');
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
