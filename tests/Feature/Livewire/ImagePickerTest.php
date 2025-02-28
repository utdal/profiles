<?php

namespace Tests\Feature\Livewire;

use Livewire\Livewire;
use App\Http\Livewire\ImagePicker;
use App\Profile;
use Tests\TestCase;
use Tests\Feature\Traits\HasUploadedImage;
use Tests\Feature\Traits\LoginWithRole;

class ImagePickerTest extends TestCase
{
    use LoginWithRole, HasUploadedImage;
    
    /** @test */
    public function can_preview_image()
    {
        $image = $this->mockUploadedImage();

        $component = Livewire::test(ImagePicker::class)
                        ->set('image', $image)
                        ->assertHasNoErrors('image');

        $component->assertSee(explode(":", $component->image)[1]);
    }

    /** @test */
    public function can_upload_profile_avatar()
    {
        $profile = Profile::factory()->hasData()->create();

        $user = $profile->user;
        
        $route = route('profiles.show', $profile);
        
        $image = $this->mockUploadedImage();

        $this->assertFalse($profile->hasImage('images'));

        $component = Livewire::actingAs($user)
                        ->test(ImagePicker::class, [
                                'model' => $profile,
                                'save_function' => 'processImage',
                                'save_params' => [
                                        'collection' => 'images',
                                    ],
                                'image_param_name' => 'new_image',
                                'callback_function' => 'updateLayoutSettings',
                                'callback_params' => [
                                    'fancy_header' => false,
                                    'fancy_header_right' => false,
                                ],
                                'redirect_route' => $route,
                                'partial_view' => 'livewire.partials._fancy-header-settings',
                                'auth_params' => ['update', [Profile::class, $profile]],
                            ]);

        $component->set('image', $image) // Updates image property
                    ->assertHasNoErrors('image')
                    ->call('save') // Runs save function and callback function to upload image and update profile
                    ->assertRedirect($route)
                    ->assertStatus(200);
    
        $profile->refresh();

        $this->get($route)
                ->assertSessionHasNoErrors()
                ->assertStatus(200)
                ->assertViewIs('profiles.show')
                ->assertSee('Profile image has been updated.')
                ->assertSee($profile->image_url);

        $this->assertFalse($profile->hasFancyHeader());
        $this->assertTrue($profile->hasImage('images'));
        $this->assertFileExists($profile->getFirstMedia('images')->getPath());

    }

    /** @test */
    public function can_upload_profile_cover()
    {
        $profile = Profile::factory()->hasData()->create();

        $user = $profile->user;
        
        $route = route('profiles.show', $profile);
        
        $image = $this->mockUploadedImage();

        $this->assertFalse($profile->hasImage('banners'));

        $component = Livewire::actingAs($user)
                        ->test(ImagePicker::class, [
                                'model' => $profile,
                                'save_function' => 'processImage',
                                'save_params' => [
                                        'collection' => 'banners',
                                    ],
                                'image_param_name' => 'new_image',
                                'callback_function' => 'updateLayoutSettings',
                                'callback_params' => [
                                    'fancy_header' => true,
                                    'fancy_header_right' => false,
                                ],
                                'redirect_route' => $route,
                                'partial_view' => 'livewire.partials._fancy-header-settings',
                                'auth_params' => ['update', [Profile::class, $profile]],
                            ]);

        $component->set('image', $image) // Updates image property
                    ->assertHasNoErrors('image')
                    ->call('save') // Runs save function and callback function to upload image and update profile
                    ->assertRedirect($route)
                    ->assertStatus(200);
    
        $profile->refresh();

        $this->get($route)
                ->assertSessionHasNoErrors()
                ->assertStatus(200)
                ->assertViewIs('profiles.show')
                ->assertSee('Profile image has been updated.')
                ->assertSee($profile->banner_url);

        $this->assertTrue($profile->hasFancyHeader());
        $this->assertTrue($profile->hasImage('banners'));
        $this->assertFileExists($profile->getFirstMedia('banners')->getPath());
    }

}
