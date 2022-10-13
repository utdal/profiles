<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Profile;

class AcademicAnalyticsPublications extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public bool $modalVisible = false;
    public Profile $profile;
    protected $listeners = ['AAPublicationsModalShown' => 'showModal'];

    public function showModal()
    {
        $this->modalVisible = true;
    }

    public function getPublicationsProperty()
    {
        $per_page = 10;
        return $this->profile
                ->getAcademicAnalyticsPublications()
                ->sortByDesc('sort_order')        
                ->paginate($per_page);
    }

    public function render()
    {
        return view('livewire.academic-analytics-publications');
    }
}
