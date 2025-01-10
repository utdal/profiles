<?php

namespace App\Http\Livewire;

use App\Profile;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Http\Requests\Concerns\HasImageUploads;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProfileHeaderEditorModal extends Component
{

    use WithFileUploads, HasImageUploads, AuthorizesRequests;

    public $user;
    public Profile $profile;
    public bool $fancy_header;
    public $image_rules;

    public function mount()
    {
        $this->image_rules = $this->uploadedImageRules();
        $this->fancy_header = $this->profile->hasFancyHeader();
    }

    public function render()
    {
        return view('livewire.profile-header-editor-modal');
    }
}
