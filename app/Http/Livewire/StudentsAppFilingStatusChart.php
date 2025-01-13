<?php

namespace App\Http\Livewire;

use App\Insights\StudentApplications\StudentDataInsight;
use Livewire\Component;

class StudentsAppFilingStatusChart extends Component
{

    public array $labels;
    public array $data;
    public $selected_semesters = [];
    public $selected_schools = [];
    public $weeks_before_semester_start;
    public $weeks_before_semester_end;
    public $selected_filing_statuses;
    protected $listeners = ['refreshData1', 'refreshChart1'];

    public function mount()
    {
        $this->weeks_before_semester_start = 4;
        $this->weeks_before_semester_end = 4;
        $this->selected_filing_statuses = ["accepted", "maybe later", "not interested", "new", "follow up"];
     
        $data = $this->dataset;
        $this->data = $data['datasets'];
        $this->labels = $data['labels'];
    }

    public function refreshChart1($data, $labels) {}

    public function refreshData1($selected_semesters, $selected_schools, $weeks_before_semester_start, $weeks_before_semester_end) {

        $this->weeks_before_semester_start = $weeks_before_semester_start;
        $this->weeks_before_semester_end = $weeks_before_semester_end;
        $this->selected_semesters = $selected_semesters;
        $this->selected_schools = $selected_schools;

        $data = $this->dataset;
        
        $this->data = $data['datasets'];
        $this->labels = $data['labels'];

        $this->emit('refreshChart1', $this->data, $this->labels);
    }

    public function getDatasetProperty()
    {
        $report = new StudentDataInsight();
        return $report->appsCountBySemestersAndSchoolsWithFilingStatus($this->selected_semesters, $this->selected_schools, $this->selected_filing_statuses, $this->weeks_before_semester_start, $this->weeks_before_semester_end);
    }

    public function render()
    {
        return view('livewire.students-app-filing-status-chart');
    }
}
