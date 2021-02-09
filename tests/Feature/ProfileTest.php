<?php

namespace Tests\Feature;

use App\Profile;
use App\ProfileData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Traits\HasJson;
use Tests\Feature\Traits\HasUploadedImage;
use Tests\Feature\Traits\LoginWithRole;
use Tests\TestCase;

/**
 * @group profile
 */
class ProfileTest extends TestCase
{
    use HasJson;
    use HasUploadedImage;
    use LoginWithRole;
    use RefreshDatabase;
    use WithFaker;

    /**
     * Test profile creation.
     *
     * @return void
     */
    public function testProfileCreation()
    {
        $this->seed();

        $profile = factory(Profile::class)->create();

        // basic info is always created with a profile
        $profile_data = factory(ProfileData::class)->create([
            'profile_id' => $profile->id,
        ]);

        $this->assertDatabaseHas('profiles', $profile->getAttributes());
        $this->assertDatabaseHas('profile_data', array_merge($profile_data->getAttributes(), [
            'profile_id' => $profile->id,
            'data' => $this->castToJson($profile_data->data),
        ]));
        $this->assertDatabaseHas('users', $profile->user->getAttributes());

        $this->get(route('profiles.home'))->assertStatus(200);
        $this->get(route('profiles.index'))
            ->assertSee(e($profile->name))
            ->assertStatus(200);
        $this->get(route('profiles.show', ['profile' => $profile]))
            ->assertStatus(200)
            ->assertSee(e($profile->name));

        $this->loginAsAdmin();
        $this->get(route('profiles.table'))->assertStatus(200);
    }

    /**
     * Test profile information editing.
     *
     * @return void
     */
    public function testProfileInformationEdit()
    {
        $this->seed();

        $profile = factory(Profile::class)->create();

        // basic info is always created with a profile
        $profile_data = factory(ProfileData::class)->create([
            'profile_id' => $profile->id,
        ]);

        $information_edit_route = route('profiles.edit', [
            'profile' => $profile,
            'section' => 'information',
        ]);

        $this->get($information_edit_route)->assertRedirect(route('login'));

        $this->loginAsAdmin();

        $this->get($information_edit_route)
            ->assertStatus(200)
            ->assertViewIs('profiles.edit')
            ->assertSeeTextInOrder(["Edit", e($profile->name), "Information"])
            ->assertSee(e($profile_data->title));

        $new_profile_displayname = $this->faker->name;
        $new_profile_data = factory(ProfileData::class)->make([
            'data->distinguished_title' => $this->faker->jobTitle,
            'data->secondary_title' => $this->faker->jobTitle,
            'data->tertiary_title' => $this->faker->jobTitle,
            'data->url' => $this->faker->url,
            'data->url_name' => 'Website1',
            'data->secondary_url' => $this->faker->url,
            'data->secondary_url_name' => 'Website2',
            'data->tertiary_url' => $this->faker->url,
            'data->tertiary_url_name' => 'Website3',
            'data->orc_id' => $this->faker->numerify('####-####-####-####'),
        ]);

        $information_update_route = route('profiles.update', [
            'profile' => $profile,
            'section' => 'information',
        ]);

        $profile_post_data = [
            'title' => $new_profile_data->title,
            'distinguished_title' => $new_profile_data->distinguished_title,
            'secondary_title' => $new_profile_data->secondary_title,
            'tertiary_title' => $new_profile_data->tertiary_title,
            'email' => $new_profile_data->email,
            'phone' => $new_profile_data->phone,
            'location' => $new_profile_data->location,
            'url' => $new_profile_data->url,
            'url_name' => $new_profile_data->url_name,
            'secondary_url' => $new_profile_data->secondary_url,
            'secondary_url_name' => $new_profile_data->secondary_url_name,
            'tertiary_url' => $new_profile_data->tertiary_url,
            'tertiary_url_name' => $new_profile_data->tertiary_url_name,
            'orc_id' => $new_profile_data->orc_id,
        ];

        $response = $this->followingRedirects()->post($information_update_route, [
            'data' => [
                1 => [
                    'id' => $profile_data->id,
                    'data' => $profile_post_data + [
                        'orc_id_managed' => 0,
                        'fancy_header' => 0,
                        'fancy_header_right' => 0,
                    ],
                ],
            ],
            'full_name' => $new_profile_displayname,
            'public' => 1,
        ]);

        // ProfileData updated?
        $response
            ->assertSessionHasNoErrors()
            ->assertStatus(200)
            ->assertViewIs('profiles.show')
            ->assertSee('Profile updated.')
            ->assertSeeText(e($new_profile_displayname))
            ->assertSeeText(e($new_profile_data->title))
            ->assertSeeText(e($new_profile_data->distinguished_title))
            ->assertSeeText(e($new_profile_data->secondary_title))
            ->assertSeeText(e($new_profile_data->tertiary_title))
            ->assertSeeText($new_profile_data->email)
            ->assertSeeText($new_profile_data->phone)
            ->assertSeeText(e($new_profile_data->location))
            ->assertSeeText(e($new_profile_data->url_name))
            ->assertSeeText(e($new_profile_data->secondary_url_name))
            ->assertSeeText(e($new_profile_data->tertiary_url_name))
            ->assertSee($new_profile_data->url)
            ->assertSee($new_profile_data->secondary_url)
            ->assertSee($new_profile_data->tertiary_url)
            ->assertSee($new_profile_data->orc_id)
            ->assertDontSee('fancy_header')
            ->assertDontSeeText('Sync');

        // With Fancy Header
        $response = $this->followingRedirects()->post($information_update_route, [
            'data' => [
                1 => [
                    'id' => $profile_data->id,
                    'data' => $profile_post_data + [
                        'orc_id_managed' => 0,
                        'fancy_header' => 1,
                        'fancy_header_right' => 0,
                    ],
                ],
            ],
            'full_name' => $new_profile_displayname,
            'public' => 1,
        ]);

        $response->assertSee('fancy_header');

        // With ORCID Syncing
        $response = $this->followingRedirects()->post($information_update_route, [
            'data' => [
                1 => [
                    'id' => $profile_data->id,
                    'data' => $profile_post_data + [
                        'orc_id_managed' => 1,
                        'fancy_header' => 1,
                        'fancy_header_right' => 0,
                    ],
                ],
            ],
            'full_name' => $new_profile_displayname,
            'public' => 1,
        ]);

        $response->assertSeeText('Sync');
    }

    /**
     * Test editing the profile image
     *
     * @return void
     */
    public function testProfileImageEdit()
    {
        $this->seed();

        $profile = factory(Profile::class)->create();

        // basic info is always created with a profile
        $profile_data = factory(ProfileData::class)->create([
            'profile_id' => $profile->id,
        ]);

        $image_update_route = route('profiles.update-image', [
            'profile' => $profile,
        ]);

        $this->loginAsAdmin();

        $response = $this->followingRedirects()->post($image_update_route, [
            'image' => $this->mockUploadedImage(),
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertStatus(200)
            ->assertViewIs('profiles.edit')
            ->assertSee('Profile image has been updated.');

        $this->assertFileExists($profile->getFirstMedia('images')->getPath());
    }

    /**
     * Test editing the profile banner image
     *
     * @return void
     */
    public function testProfileBannerEdit()
    {
        $this->seed();

        $profile = factory(Profile::class)->create();

        // basic info is always created with a profile
        $profile_data = factory(ProfileData::class)->create([
            'profile_id' => $profile->id,
        ]);

        $image_update_route = route('profiles.update-banner', [
            'profile' => $profile,
        ]);

        $this->loginAsAdmin();

        $response = $this->followingRedirects()->post($image_update_route, [
            'banner_image' => $this->mockUploadedImage(),
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertStatus(200)
            ->assertViewIs('profiles.edit')
            ->assertSee('Profile image has been updated.');

        $this->assertFileExists($profile->getFirstMedia('banners')->getPath());
    }

}
