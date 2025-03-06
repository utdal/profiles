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
    public $image;
    public bool $fancy_header;
    public bool $fancy_header_right;
    public $avatar_settings;
    public $cover_settings;

    public function mount()
    {
        $message = "Profile layout has been updated.";

        $this->fancy_header = $this->profile->hasFancyHeader();
        $this->fancy_header_right = $this->profile->hasFancyHeaderRight();

        $this->avatar_settings = [
            'existing_image_url' => $this->profile->image_url,
            'custom_key' => "profile-img",
            'custom_msg' => "This photo will appear on your profile page and as your application profile image - please use a high-quality image (300x300 pixels or larger).",
            'save_function' => 'processImage',
            'save_params' => [
                    'collection' => 'images',
                ],
                'image_param_name' => 'new_image',
            'callback_function' => 'updateLayoutSettings',
            'callback_params' => [
                'fancy_header' => false,
                'fancy_header_right' => $this->fancy_header_right,
            ],
            'redirect_route' => route('profiles.show', $this->profile->slug),
            'message' => $message,
            'auth_params' => ['update', [Profile::class, $this->profile]],
        ];

        $this->cover_settings = [
            'existing_image_url' => $this->profile->banner_url,
            'custom_key' => "banner-img",
            'custom_msg' => "This will use a full-width header style - please use a high-quality image (1280 × 720 pixels or larger).",
            'save_function' => 'processImage',
            'save_params' => [
                    'collection' => 'banners',
                ],
                'image_param_name' => 'new_image',
            'callback_function' => 'updateLayoutSettings',
            'callback_params' => [
                'fancy_header' => true,
                'fancy_header_right' => $this->fancy_header_right,
            ],
            'redirect_route' => route('profiles.show', $this->profile->slug),
            'message' => $message,
            'partial_view' => 'livewire.partials._fancy-header-settings',
            'auth_params' => ['update', [Profile::class, $this->profile]],
        ];
    }

    public function render()
    {
        return view('livewire.profile-header-editor-modal');
    }
}
