<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\ProfileData;

class ImportAaPublication extends Component
{
    public ProfileData $publication;

    //public $publications = [];

/*     protected $listeners = ['addToEditor', 'removeFromEditor'];

    public function addToEditor($publication)
    {
        $this->publication->imported = true;

        array_push($this->publications, $publication);

        $this->emit('alert', "Added to the Editor!", 'success');
    }

    public function removeFromEditor($publication)
    {
        $this->publication->imported = false;

        //Remove publication from array

        $this->emit('alert', "Removed from the Editor", 'success');
    } */

    public function updatePublicationsStatus(){
        //When they click on "I'm done" I will send the array of publications to the parent to preserve the status of publications selected for whenever they click on the modal again
    }

    public function render()
    {
        return view('livewire.import-aa-publication');
    }
}
