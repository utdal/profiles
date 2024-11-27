<?php

namespace App\Http\Livewire;

use App\Profile;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Http\Requests\Concerns\HasImageUploads;
use App\ProfileData;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BannerPictureEditorModal extends Component
{
    use WithFileUploads, HasImageUploads, AuthorizesRequests;

    public Profile $profile;
    public ProfileData $info;
    public bool $fancy_header;
    public bool $fancy_header_right;
    public $banner_image;
    public $user;
    protected $listeners = ['removeFancyHeader'];

    public function mount()
    {
        $this->fancy_header = $this->info->fancy_header;
        $this->fancy_header_right = $this->info->fancy_header_right;
    }

    public function updatedBannerImage()
    {
        $this->validate([
            'banner_image' => $this->uploadedImageRules(),
            'fancy_header_right' => 'boolean',
        ]);
    }

    public function submit()
    {
        $this->authorize('update', $this->profile);
        
        $message = $this->profile->processImage($this->banner_image, 'banners');
        
        $profile_info = $this->profile->information->first();

        $profile_info->data = array_merge($profile_info->data ?? [], [
            'fancy_header' => true,
            'fancy_header_right' => (bool) $this->fancy_header_right,
        ]);
        
        $updated = $profile_info->save();
        
        if ($updated) {
            return redirect(route('profiles.show', $this->profile))->with('flash_message', $message);
        }
        
        return back()->withErrors(['update' => 'Failed to update profile information. Please try again.']);
        
    }

    public function removeFancyHeader()
    {
        $this->authorize('update', $this->profile);

        $profile_info = $this->profile->information->first();

        $profile_info->data = array_merge($profile_info->data ?? [], [
            'fancy_header' => false,
            'fancy_header_right' => false,
        ]);
        
        $updated = $profile_info->save();
        
        if ($updated) {
            session()->flash('flash_message', 'Fancy header has been removed.');
            return redirect()->route('profiles.show', $this->profile);
        }
        
        return back()->withErrors(['update' => 'Failed to update profile information. Please try again.']);
    }


    public function render()
    {
        return view('livewire.banner-picture-editor-modal');
    }
}
