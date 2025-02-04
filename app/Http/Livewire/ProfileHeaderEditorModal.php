<?php

namespace App\Http\Livewire;

use App\Profile;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Http\Requests\Concerns\HasImageUploads;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\TemporaryUploadedFile;

class ProfileHeaderEditorModal extends Component
{

    use WithFileUploads, HasImageUploads, AuthorizesRequests;

    public $user;
    public Profile $profile;
    public $image;
    public bool $fancy_header;
    public bool $fancy_header_right;
    public bool $banner_image_exists;
    public $message = "Profile layout has been updated.";

    protected $listeners = ['updateImage'];

    public function mount()
    {
        $this->fancy_header = $this->profile->hasFancyHeader();
        $this->banner_image_exists = $this->profile->hasMedia('banners');
        $this->fancy_header_right = $this->profile->hasFancyHeaderRight();
    }

    public function updateImage($image_path)
    {
        $this->image = new TemporaryUploadedFile($image_path, config('filesystems.default'));
    }
    
    public function submit()
    {
        $this->authorize('update', $this->profile);
        
        $profile_info = $this->profile->information->first();
        
        if ($this->fancy_header) {
               $this->validate(['fancy_header_right' => 'boolean']);
        }
        
        if (!is_null($this->image)) {
           $this->message = $this->profile->processImage($this->image, $this->fancy_header ? 'banners' : 'images');
            
        }

        $profile_info->data = array_merge($profile_info->data ?? [], [
                                'fancy_header' => $this->fancy_header,
                                'fancy_header_right' => (bool) $this->fancy_header_right,
                            ]);

        $profile_info->save();

        $this->banner_image_exists = $this->fancy_header;

        $this->image = null;

        redirect()->route('profiles.show', $this->profile)->with('flash_message', $this->message);
    }

    public function render()
    {
        return view('livewire.profile-header-editor-modal');
    }
}
