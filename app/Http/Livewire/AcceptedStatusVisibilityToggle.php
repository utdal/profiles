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

    public $accepted_stats;

    public $visibility_map = [];

    public function mount($student, $accepted_stats)
    {
        $this->student = $student;

        foreach ($accepted_stats as $profile_key => $record) {
            $this->visibility_map[$profile_key] = !isset($record['visible']) || $record['visible'] === '1';
        }
    }

    public function saveDisplayPreferences()
    {
        $this->authorize('update', [$this->student, $this->user]);

        foreach ($this->visibility_map as $profile_key => $visible) {
            $this->student->stats->removeData("accepted_by.{$profile_key}.visible");
            $this->student->stats->insertData([
                'accepted_by' => [
                    $profile_key => ['visible' => $visible ? '1' : '0']
                ]
            ]);
        }
        $this->refreshAcceptedStatusVisibility();
        $this->emit('alert', 'Display preferences saved!', 'success');
    }

    public function refreshAcceptedStatusVisibility()
    {
        $accepted_by = $this->student->fresh()->stats->accepted_by;
        $this->dispatchBrowserEvent('refreshAcceptedStatusVisibility', $accepted_by);
    }
}