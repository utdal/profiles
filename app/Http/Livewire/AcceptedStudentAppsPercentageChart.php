<?php

namespace App\Http\Livewire;

use App\Helpers\Semester;
use App\Insights\StudentApplications\StudentDataInsight;
use Livewire\Component;

class AcceptedStudentAppsPercentageChart extends Component
{

    public array $labels;
    public array $data;
    public array $selected_semesters;
    public array $selected_schools;
    public $selected_filing_statuses;
    protected $listeners = ['refreshData3', 'refreshChart3'];


    public function mount($selected_semesters, $selected_schools)
    {
        $this->selected_semesters = [Semester::current()];
        //$this->selected_schools = $selected_schools;
        // $this->selected_filing_statuses = ["accepted"];
    }

    public function refreshChart3($data, $labels) {}

    public function refreshData3($selected_semesters, $selected_schools) {
        $this->selected_semesters = $selected_semesters;
        $this->selected_schools = $selected_schools;

        $data = $this->getData();
        
        $this->data = $data['datasets'];
        $this->labels = $data['labels'];

        $this->emit('refreshChart3', $this->data, $this->labels);
    }

    public function getData()
    {
        $report = new StudentDataInsight();
        return $report->transformDataAcceptedInCurrentSeason($this->selected_semesters, $this->selected_schools);
    }

    public function render()
    {
        $data = $this->getData();
        $this->data = $data['datasets'];
        $this->labels = $data['labels'];

        return view('livewire.accepted-student-apps-percentage-chart');
    }
}
