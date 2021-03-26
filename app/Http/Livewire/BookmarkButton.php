<?php

namespace App\Http\Livewire;

use Livewire\Component;

class BookmarkButton extends Component
{
    public $model;

    public $user;

    public function mount()
    {
        $this->user = auth()->user();
    }

    public function render()
    {
        return view('livewire.bookmark-button');
    }

    public function bookmark()
    {
        $this->user->bookmark($this->model);
    }

    public function unbookmark()
    {
        $this->user->unbookmark($this->model);
    }
}
