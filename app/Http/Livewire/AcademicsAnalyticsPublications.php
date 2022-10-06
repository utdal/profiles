<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Profile;

class AcademicsAnalyticsPublications extends Component
{
    //public bool $modal_visible;
    public Profile $profile;
    public $publications;

    protected $listeners = ['loadPublications' => 'loadPublications()'];

    public function mount()
    {
        //$this->modal_visible = true;
        $this->publications = $this->profile->getAcademicsAnalyticsPublications();
    }

    public function render()
    {
       // dd($this->profile, $this->publications);
        return view('livewire.academics-analytics-publications', [
            'profile' => $this->profile,
            //'modal_visible' => $this->modal_visible,
            'publications' => $this->publications,
        ]);
    }
}
