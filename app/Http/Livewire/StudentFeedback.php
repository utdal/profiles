<?php

namespace App\Http\Livewire;

use App\StudentFeedback as StudentFeedbackEntry;
use Livewire\Component;

class StudentFeedback extends Component
{
    public $student;

    public $new_feedback = [];

    public function add()
    {
        $feedback = $this->student->feedback()->create([
            'data' => $this->new_feedback + ['submitted_by' => auth()->user()->id ?? 'system'],
        ]);

        if ($feedback) {
            $this->emit('alert', "Feedback saved. Thank you!", 'success');
            $this->new_feedback = [];
        } else {
            $this->emit('alert', "Unable to save feedback", 'danger');
        }
    }

    public function destroy(StudentFeedbackEntry $feedback)
    {
        $feedback->delete();

        $this->emit('alert', "Feedback removed.", 'success');
    }

    public function render()
    {
        return view('livewire.student-feedback', [
            'feedback' => $this->student->feedback()->orderBy('created_at', 'desc')->get(),
            'reasons' => StudentFeedbackEntry::REASONS,
        ]);
    }
}
