<?php

namespace App\Http\Livewire;

use App\Http\Requests\Concerns\HasImageUploads;
use Illuminate\Support\Facades\Validator;
use App\Profile;
use Livewire\Component;
use Livewire\WithFileUploads;

class ImagePicker extends Component
{
    use WithFileUploads, HasImageUploads;

    public $image;
    public $existing_image_url;
    public $trigger;
    public $custom_key;
    public $custom_msg;
    public Profile $profile;

    public function updatedImage()
    {
        if ($this->image) {
           
            $this->validate(
                ['image' => "nullable|{$this->uploadedImageRules()}"],
            );
            
            $this->emitTo($this->trigger, 'updateImage', $this->image->getRealPath()) ;
        }
    }

    public function render()
    {   
        return view('livewire.image-picker');
    }
}

