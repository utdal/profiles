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
    public $selected_filing_statuses;
    protected $listeners = ['refreshData1', 'refreshChart1'];

    public function mount()
    {
        $this->selected_filing_statuses = ["accepted", "maybe later", "not interested", "new", "follow up"];
    }

    public function refreshChart1($data, $labels) {}

    public function refreshData1($selected_semesters, $selected_schools) {

        $this->selected_semesters = $selected_semesters;
        $this->selected_schools = $selected_schools;

        $data = $this->getData();
        
        $this->data = $data['datasets'];
        $this->labels = $data['labels'];

        $this->emit('refreshChart1', $this->data, $this->labels);
    }

    public function getData()
    {
        $report = new StudentDataInsight();
        return $report->getAppsBySemestersAndSchoolsWithFilingStatus($this->selected_semesters, $this->selected_filing_statuses, $this->selected_schools);
    }

    public function render()
    {
        $data = $this->getData();
        $this->data = $data['datasets'];
        $this->labels = $data['labels'];

        return view('livewire.students-app-filing-status-chart', [
            'data' => $this->data,
            'labels' => $this->labels,
        ]);
    }
}
