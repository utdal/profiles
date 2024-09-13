<?php

namespace App\Http\Livewire;

use Livewire\Component;

class InsightsFilter extends Component
{

    public $semester_options = [];
    public $school_options = [];
    protected $listeners = ['applyFilters'];

    public function applyFilters($selected_semesters, $selected_schools) {
        $this->emitTo('students-app-count-chart', 'refreshData2', $selected_semesters, $selected_schools);
        $this->emitTo('students-app-filing-status-chart', 'refreshData1', $selected_semesters, $selected_schools);
        $this->emitTo('accepted-and-follow-up-apps-percentage-chart', 'refreshData5', $selected_semesters, $selected_schools);
        $this->emitTo('student-apps-viewed-not-viewed-chart', 'refreshData4', $selected_semesters, $selected_schools);
    }

    public function render()
    {
        return view('livewire.insights-filter');
    }
}
