<?php

namespace App\Http\Livewire;

use App\User;
use App\Student;
use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ToggleStudentAppAcceptedStatus extends Component
{
    use AuthorizesRequests; 

    /** @var User */
    public $user;

    public Student $student;

    public $visible;

    public $stats;

    public function mount()
    {
        $this->user = auth()->user();
        $this->syncStatsVisibility();
    }

    public function updatedVisible($value)
    {
        $this->authorize('update', [$this->student, $this->user]);

        $this->stats->removeData('accepted_status_history_visibility');
        $this->stats->insertData(['accepted_status_history_visibility' => $value ? '1' : '0']);
        $this->emit('alert', $value
            ? "Now Displaying Last Accepted Status!"
            : "Last Accepted Status is Hidden Now.",
            'success'
        );

        $this->syncStatsVisibility();
    }

    private function syncStatsVisibility()
    {
        $this->stats = $this->student->fresh()->stats;
        $this->visible = !isset($this->stats->accepted_status_history_visibility) || $this->stats->accepted_status_history_visibility === '1' ? true : false;
    }
}
