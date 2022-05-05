<?php

namespace App\Http\Livewire;

use Livewire\Component;

class BookmarkButton extends Component
{
    public $model;

    public $user;

    public $mini = false;

    public $simple = false;

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

        $this->emit('alert', "Bookmarked!", 'success');
    }

    public function unbookmark()
    {
        $this->user->unbookmark($this->model);

        $this->emit('alert', "Removed from your bookmarks", 'success');
    }
}
