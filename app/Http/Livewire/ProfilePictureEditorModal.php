<?php

namespace App\Http\Livewire;

use App\Profile;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Http\Requests\Concerns\HasImageUploads;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProfilePictureEditorModal extends Component
{
    use WithFileUploads;
    use HasImageUploads;
    use AuthorizesRequests;
    public Profile $profile;
    public $image;
    public $user;

    public function mount()
    {
        $this->user = auth()->user();
    }

    public function updatedImage()
    {
        $this->validate([
            'image' => $this->uploadedImageRules(),
        ]);
    }

    public function submit()
    {
        $this->authorize('update', $this->user, $this->profile);
        
        $msg = $this->profile->processImage($this->image);
        
        return redirect(route('profiles.show', $this->profile))->with('flash_message', $msg, 'images');
    }

    public function render()
    {
        return view('livewire.profile-picture-editor-modal');
    }
}
