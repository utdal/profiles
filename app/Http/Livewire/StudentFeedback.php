<?php

namespace App\Http\Livewire;

use App\StudentFeedback as StudentFeedbackEntry;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Computed;
use Livewire\Component;

class StudentFeedback extends Component
{
    use AuthorizesRequests;

    public $student;

    public $new_feedback = [];

    #[Computed()]
    public function feedback()
    {
        return $this->student
            ->feedback()
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function add()
    {
        $this->authorize('create', StudentFeedbackEntry::class);

        $feedback = $this->student->feedback()->create([
            'data' => $this->new_feedback + ['submitted_by' => auth()->user()->id ?? 'system'],
        ]);

        if ($feedback) {
            $this->dispatch('alert', "Feedback saved. Thank you!", 'success');
            $this->new_feedback = [];
        } else {
            $this->dispatch('alert', message: "Unable to save feedback", type: 'danger');
        }
    }

    public function destroy(StudentFeedbackEntry $feedback)
    {
        $this->authorize('delete', $feedback);

        $feedback->delete();

        $this->dispatch('alert', message: "Feedback removed.", type: 'success');
    }

    public function render()
    {
        return view('livewire.student-feedback', [
            'reasons' => StudentFeedbackEntry::REASONS,
        ]);
    }
}
