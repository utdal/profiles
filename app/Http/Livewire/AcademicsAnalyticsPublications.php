<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Profile;

class AcademicsAnalyticsPublications extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public Profile $profile;

    public function data()
    {   
        $per_page = 10;
        return $this->profile
                    ->getAcademicsAnalyticsPublications()
                    ->sortByDesc('sort_order')        
                    ->paginate($per_page);
    }

    public function render()
    {
        return view('livewire.academics-analytics-publications', [
            'profile' => $this->profile,
            'publications' => $this->data(),
        ]);
    }
}
