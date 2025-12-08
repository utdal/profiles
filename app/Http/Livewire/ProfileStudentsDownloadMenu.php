<?php

namespace App\Http\Livewire;

use Illuminate\Http\Request;
use Livewire\Component;
use App\Http\Livewire\Concerns\HasFilters;
use App\StudentData;
use Illuminate\Support\Str;

class ProfileStudentsDownloadMenu extends Component
{
    use HasFilters;

    public $application_scope;
    
    public $file_format = 'pdf';
    
    public $filter_summary;
    
    public $applied_filters = [];
    
    public $filing_status = '';

    protected $listeners = [
        'updateFilterSummary',
        'updateFilingStatus',
        'updateAppliedFilters',
        'resetMenu',
    ];

    protected $rules = [
        'application_scope' => 'required|in:all,filtered',
        'file_format' => 'required|in:pdf,excel',
    ];

    public function mount(Request $request)
    {
        $this->applied_filters = $request->all();
        $this->updateFilterSummary();
    }

    public function updateAppliedFilters($applied_filters)
    {
        $this->applied_filters = $applied_filters;
        $this->updateFilterSummary();
    }

    public function updateFilingStatus($filing_status)
    {
        $this->filing_status = $filing_status;
        $this->updateFilterSummary();
    }

    public function updateFilterSummary()
    {
        if (isset($this->applied_filters) || isset($this->filing_status)) {

            $filing_status = !empty($this->filing_status) ? 'Filed as: ' . ucfirst($this->filing_status) . '. ' : '';
            $filters = count($this->applied_filters) > 0 ? $this->humanizeFilters($this->applied_filters)->implode(', ') . '. ' : '';

            $this->filter_summary = "{$filing_status}{$filters}";
            $this->application_scope = 'filtered';
        }
        else {
            $this->filter_summary = '';
            $this->application_scope = 'all';
        }
    }

    public function humanizeFilters($filters)
    {
        $filter_value_names = [
            'credit' => [
                '0' => 'Volunteer',
                '1' => 'Credit',
                '-1' => 'No preference'
            ],
            'language' => StudentData::$languages,
        ];
        
        return collect($filters)->map(function ($value, $alias) use ($filter_value_names) {
                
                $alias = Str::before($alias, '_filter');
                
                $label = ucfirst(str_replace('_', ' ', $alias));
                
                if (isset($filter_value_names[$alias][$value])) {
                    $value = $filter_value_names[$alias][$value];
                }
                elseif (in_array($value, ['0', '1', '-1'])) {
                    $value = ['0' => 'No', '1' => 'Yes', '-1' => 'n/a'][$value];
                }
                return "{$label}: {$value}";
            });
    }

    public function download()
    {
        $this->validate();

        $download_all = $this->application_scope === 'all';
        $format = $this->file_format;

        if ($format === 'pdf') {
            $this->emitTo('profile-students', 'downloadAsPdf', null, $download_all, $this->filter_summary);
        } else {
            $this->emitTo('profile-students', 'downloadAsExcel', $download_all);
        }
    }

    public function resetMenu() 
    {
        $this->reset(['application_scope', 'file_format', 'applied_filters']);
        $this->updateFilterSummary();
    }

    public function render()
    {
        return view('livewire.profile-students-download-menu');
    }
}
