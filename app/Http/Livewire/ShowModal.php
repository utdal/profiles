<?php

namespace App\Http\Livewire;

use App\Profile;
use Livewire\Component;

class ShowModal extends Component
{
    public bool $modalVisible = false;
    public Profile $profile;
    protected $listeners = ['showModal'];

    public function showModal()
    {
        $this->emit('loadPublications');
    }
}
