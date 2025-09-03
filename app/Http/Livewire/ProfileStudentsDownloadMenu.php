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
    
    public $applied_filters;

    protected $listeners = [
        'updateFilterSummary', 
        'resetMenu',
    ];

    protected $rules = [
        'application_scope' => 'required|in:all,filtered',
        'file_format' => 'required|in:pdf,excel',
    ];

    public function mount(Request $request)
    {
        $applied_filters = [
            'filters' => $request->all(),
        ];
        
        $this->updateFilterSummary($applied_filters);
    }

    public function updateFilterSummary($applied_filters = null)
    {
        if (isset($applied_filters)) {

            $filing_status = !empty($applied_filters['filing_status']) ? 'Filed as: ' . ucfirst($applied_filters['filing_status']) . '. ' : '';
            $filters = count($applied_filters['filters']) > 0 ? $this->humanizeFilters($applied_filters['filters'])->implode(', ') : '';

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
            $this->emitTo('profile-students', 'downloadAsPdf', $download_all, $this->filter_summary);
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
