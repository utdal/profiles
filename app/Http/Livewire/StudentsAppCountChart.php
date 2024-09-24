<?php

namespace App\Http\Livewire;

use App\Helpers\Semester;
use App\Insights\StudentApplications\StudentDataInsight;
use Livewire\Component;

class StudentsAppCountChart extends Component
{

    public array $labels;
    public array $data;
    public $current;
    public $selected_semesters = [];
    public $selected_schools = [];
    protected $listeners = ['refreshData2', 'refreshChart2'];

    public function mount() 
    {
        $this->current = Semester::current();
    }

    public function refreshChart2($data, $labels) {}

    public function refreshData2($selected_semesters, $selected_schools) {

        $this->selected_semesters = $selected_semesters;
        $this->selected_schools = $selected_schools;

        $data = $this->getData();
        
        $this->data = $data['datasets'];
        $this->labels = $data['labels'];

        $this->emit('refreshChart2', $this->data, $this->labels);
    }

    public function getData()
    {
        $report = new StudentDataInsight();
        return $report->getAppsBySemestersAndSchools($this->selected_semesters, $this->selected_schools);
    }

    public function render()
    {
        $data = $this->getData();
        $this->data = $data['datasets'];
        $this->labels = $data['labels'];

        return view('livewire.students-app-count-chart', [
            'data' => $this->data,
            'labels' => $this->labels,
            'current' => [$this->current],
        ]);
    }
}
