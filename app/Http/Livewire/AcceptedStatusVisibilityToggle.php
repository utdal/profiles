<?php

namespace App\Http\Livewire;

use App\User;
use App\Student;
use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AcceptedStatusVisibilityToggle extends Component
{
    use AuthorizesRequests; 

    /** @var User */
    public $user;

    public Student $student;
    
    public int $profile_id;
    
    public string $profile_name;

    public $visible;

    public $stats;

    public function mount()
    {
        $this->user = auth()->user();
        $this->syncStatsVisibility();
    }

    public function toggleVisibility()
    {
        $this->authorize('update', [$this->student, $this->user]);

        $value = $this->visible = !$this->visible;

        $this->stats->removeData("accepted_by.profile_{$this->profile_id}.visible");
        $this->stats->insertData(['accepted_by' => ["profile_{$this->profile_id}" => ['visible' => $value ? '1' : '0']]]);
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
        $this->visible = !isset($this->stats->accepted_by["profile_{$this->profile_id}"]['visible']) || $this->stats->accepted_by["profile_{$this->profile_id}"]['visible'] === '1' ? true : false;
    }
}
