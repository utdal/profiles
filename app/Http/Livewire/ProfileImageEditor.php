<?php

namespace App\Http\Livewire;

class ProfileImageEditor extends ProfileHeaderEditorModal
{
    public $image;

    public function updatedImage()
    {
        $this->validate([
            'image' => $this->image_rules
        ]);
    }

    public function submit()
    {
        $this->authorize('update', $this->profile);

        $message = "Profile layout has been updated.";

        $this->validate([
            'image' => "nullable|{$this->image_rules}"
        ]);

        if (!is_null($this->image))
        {
            $message = $this->profile->processImage($this->image, 'images');
        }

        $profile_info = $this->profile->information->first();

        $profile_info->data = array_merge($profile_info->data ?? [], ['fancy_header' => false]);

        $profile_info->save();
        
        redirect()->route('profiles.show', $this->profile)->with('flash_message', $message);
    }

    public function render()
    {
        return view('livewire.profile-image-editor');
    }

}
