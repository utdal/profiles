<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class BannerImageEditor extends ProfileHeaderEditorModal
{

    public $fancy_header_right;
    public $banner_image;
    public $banner_image_exists;
    protected $listeners = ['removeFancyHeader'];

    public function mount()
    {
        $this->banner_image_exists = $this->profile->hasMedia('banners');
        $this->fancy_header_right = $this->profile->hasFancyHeaderRight();
    }

    public function updatedBannerImage()
    {
        $this->validate([
            'banner_image' => $this->image_rules,
            'fancy_header_right' => 'boolean',
        ]);
    }

    public function submit()
    {
        $this->authorize('update', $this->profile);

        $message = "Profile layout has been updated.";

        $this->validate([
                        'banner_image' => "nullable|{$this->image_rules}", 
                        'fancy_header_right' => 'boolean'
                    ]);

        if (!is_null($this->banner_image)) 
        {
            $message = "Profile cover image has been updated.";

            $this->profile->processImage($this->banner_image, 'banners');
        }

        $profile_info = $this->profile->information->first();

        $profile_info->data = array_merge($profile_info->data ?? [], [
                                'fancy_header' => true,
                                'fancy_header_right' => (bool) $this->fancy_header_right,
                            ]);

        $profile_info->save();

        redirect()->route('profiles.show', $this->profile)->with('flash_message', $message);
    }

    public function render()
    {   
        return view('livewire.banner-image-editor');
    }
}
