<?php

namespace App\Http\Livewire\Insights;

use App\Helpers\Semester;
use App\Insights\StudentApplications\StudentDataInsight;
use Livewire\Component;

class InsightsFilter extends Component
{

    public $semester_options = [];
    public $semesters_selected = [];
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
        $this->emitTo('insights.accepted-and-follow-up-apps-percentage-chart', 'refreshAcceptedFollowUpData', $selected_semesters, $selected_schools, $weeks_before_semester_start, $weeks_before_semester_end);
        $this->emitTo('insights.student-apps-viewed-not-viewed-chart', 'refreshViewedNotViewedData', $selected_semesters, $selected_schools, $weeks_before_semester_start, $weeks_before_semester_end);
        $this->emitTo('insights.student-apps-count-chart', 'refreshAppsCountData', $selected_semesters, $selected_schools, $weeks_before_semester_start, $weeks_before_semester_end);
        $this->emitTo('insights.student-apps-filing-status-chart', 'refreshAppsFilingStatusData', $selected_semesters, $selected_schools, $weeks_before_semester_start, $weeks_before_semester_end);
    }

    public function render()
    {
        return view('livewire.charts.insights-filter');
    }
}
