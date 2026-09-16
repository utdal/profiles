<?php

namespace App\Http\Livewire;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class BookmarkButton extends Component
{
    use AuthorizesRequests;

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
        $this->authorize('create', 'App\Bookmark');

        $this->user->bookmark($this->model);

        $this->emit('alert', "Bookmarked!", 'success');
    }

    public function unbookmark()
    {
        $this->authorize('delete', $this->user->bookmarkFor($this->model));

        $this->user->unbookmark($this->model);

        $this->emit('alert', "Removed from your bookmarks", 'success');
    }
}
