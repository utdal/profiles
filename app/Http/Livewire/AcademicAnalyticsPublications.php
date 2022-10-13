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
    //public $publications;
    public Profile $profile;
    protected $listeners = ['showModal'];



    public function showModal()
    {   
        $this->modalVisible = true;
    }

    public function getPublications()
    {
        $per_page = 10;
        return $this->publications = $this->profile
                    ->getAcademicAnalyticsPublications()
                    ->sortByDesc('sort_order')        
                    ->paginate($per_page);
    }

    public function render()
    {
        if ($this->modalVisible) {
            //dd($this->profile);
            $data = [
                'profile' =>  $this->profile,
                'publications' => $this->getPublications(),
                'modalVisible' => $this->modalVisible,
            ];
        }
        else {
            $data = [ 'modalVisible' =>   $this->modalVisible,
                     //'publications' => [],
                     'profile' => $this->profile ];

        }

        return view('livewire.academic-analytics-publications', $data);
    }
}
