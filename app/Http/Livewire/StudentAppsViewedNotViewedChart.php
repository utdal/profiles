<?php

namespace App\Http\Livewire;

use App\Helpers\Semester;
use App\Insights\StudentApplications\StudentDataInsight;
use Livewire\Component;

class StudentAppsViewedNotViewedChart extends Component
{

    public array $labels;
    public array $data;
    public array $selected_semesters;
    public array $selected_schools;
    public $selected_filing_statuses;
    protected $listeners = ['refreshData4', 'refreshChart4'];

    public function refreshChart4($data, $labels) {}

    public function refreshData4($selected_semesters, $selected_schools) {
        $this->selected_semesters = $selected_semesters;
        $this->selected_schools = $selected_schools;

        $data = $this->getData();
        
        $this->data = $data['datasets'];
        $this->labels = $data['labels'];

        $this->emit('refreshChart4', $this->data, $this->labels);
    }

    public function getData()
    {
        $report = new StudentDataInsight();
        return $report->getViewedAndNotViewedApps($this->selected_semesters, $this->selected_schools);
    }

    public function render()
    {
        $data = $this->getData();

        $this->data = $data['datasets'];
        $this->labels = $data['labels'];
        return view('livewire.student-apps-viewed-not-viewed-chart');
    }
}
