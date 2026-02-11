<?php

namespace App\Http\Livewire\Insights;

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
    protected $listeners = [
                    'refreshViewedNotViewedData' => 'refreshData', 
                    'refreshViewedNotViewedChart' => 'refreshChart'
                ];

    public function mount()
    {
        $data = $this->dataset;
        $this->data = $data['datasets'];
        $this->labels = $data['labels'];    
    }

    public function refreshChart($data, $labels) {}

    public function refreshData($selected_semesters, $selected_schools) {
        $this->selected_semesters = $selected_semesters;
        $this->selected_schools = $selected_schools;

        $data = $this->dataset;
        
        $this->data = $data['datasets'];
        $this->labels = $data['labels'];

        $this->emit('refreshViewedNotViewedChart', $this->data, $this->labels);
    }

    public function getDatasetProperty()
    {
        $report = new StudentDataInsight();
        return $report->appCountViewedAndNotViewed($this->selected_semesters, $this->selected_schools);
    }

    public function render()
    {
        return view('livewire.charts.student-apps-viewed-not-viewed-chart');
    }
}
