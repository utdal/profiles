<?php

namespace App\Http\Livewire;

use App\Helpers\Semester;
use App\Insights\StudentApplications\StudentDataInsight;
use Livewire\Component;

class AcceptedAndFollowUpAppsPercentageChart extends Component
{

    public array $labels;
    public array $data;
    public array $selected_semesters;
    public array $selected_schools;
    public $selected_filing_statuses;
    protected $listeners = ['refreshData5', 'refreshChart5'];

    public function refreshChart5($data, $labels) {}

    public function refreshData5($selected_semesters, $selected_schools) {
        $this->selected_semesters = $selected_semesters;
        $this->selected_schools = $selected_schools;

        $data = $this->getData();
        $this->data = $data;
        $this->labels = ['Accepted & Follow Up', 'Other'];

        $this->emit('refreshChart5', $this->data, $this->labels);
    }

    public function getData()
    {
        $report = new StudentDataInsight();
        return $report->getAppsForSemestersAndSchoolsWithFilingStatuses($this->selected_semesters, $this->selected_schools, ["accepted", "follow up"]);
    }

    public function render()
    {
        $data = $this->getData();
        $this->data = $data;
        $this->labels = ['Accepted & Follow Up', 'Other'];

        return view('livewire.accepted-and-follow-up-apps-percentage-chart');
    }
}
