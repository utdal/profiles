<?php

namespace App\Http\Livewire;

use App\Helpers\Semester;
use App\Insights\StudentApplications\StudentDataInsight;
use Livewire\Component;

class InsightsFilter extends Component
{

    public $semester_options = [];
    public $school_options = [];
    public $charts_loaded;
    public $title;
    public $current_semester;
    protected $listeners = ['applyFilters'];

    public function mount()
    {
        $this->charts_loaded = true;
        $this->current_semester = Semester::current();
    }

    public function applyFilters($selected_semesters, $selected_schools, $weeks_before_semester_start, $weeks_before_semester_end) {
        $this->charts_loaded = false;
        $this->title = StudentDataInsight::convertParameterstoTitle($selected_semesters, $selected_schools);
        $this->emitTo('accepted-and-follow-up-apps-percentage-chart', 'refreshData5', $selected_semesters, $selected_schools, $weeks_before_semester_start, $weeks_before_semester_end);
        $this->emitTo('student-apps-viewed-not-viewed-chart', 'refreshData4', $selected_semesters, $selected_schools, $weeks_before_semester_start, $weeks_before_semester_end);
        $this->emitTo('students-app-count-chart', 'refreshData2', $selected_semesters, $selected_schools, $weeks_before_semester_start, $weeks_before_semester_end);
        $this->emitTo('students-app-filing-status-chart', 'refreshData1', $selected_semesters, $selected_schools, $weeks_before_semester_start, $weeks_before_semester_end);
    }

    public function render()
    {
        return view('livewire.insights-filter');
    }
}
