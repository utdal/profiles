<?php

namespace App\Http\Livewire;

class ProfileStudentsExportMenu extends ProfileStudents
{
    public $filter_summary;

    public $show_export_all = false;

    public $show_export_filtered = false;

    protected $listeners = ['updateFilterSummary', 'clearFilterSummary'];

    public function toggleExportAll()
    {
        $this->show_export_all = !$this->show_export_all;
        $this->show_export_filtered = false;

        if ($this->show_export_all) {
            $this->dispatchBrowserEvent('flashExportButtons', ['target' => 'all']);
        }
    }

    public function toggleExportFiltered()
    {
        $this->show_export_filtered = !$this->show_export_filtered;
        $this->show_export_all = false;

        if ($this->show_export_filtered) {
            $this->dispatchBrowserEvent('flashExportButtons', ['target' => 'filtered']);
        }
    }

    public function updateFilterSummary($filter_summary)
    {
        $this->filter_summary = $filter_summary;
    }

    public function clearFilterSummary()
    {
        $this->filter_summary = '';
        $this->show_export_filtered = false;
        $this->show_export_all = false;
    }

    public function render()
    {
        return view('livewire.profile-students-export-menu');
    }
}
