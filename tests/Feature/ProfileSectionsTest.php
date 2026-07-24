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
 * @group profile_sections
 */
class ProfileSectionsTest extends TestCase
{
    use HasJson;
    use HasUploadedImage;
    use LoginWithRole;
    use RefreshDatabase;
    use WithFaker;

    /**
     * Indicates whether the default seeder should run before each test.
     *
     * @var bool
     */
    protected $seed = true;

    public function testProfilePatentsEdit(): void
    {
        $profile = Profile::factory()->hasData()->create();
        $profile_data = ProfileData::factory()->patents()->create(['profile_id' => $profile->id]);

        $edit_route = route('profiles.edit', ['profile' => $profile, 'section' => 'patents']);

        // Guests are redirected to the login page
        $this->get($edit_route)->assertRedirect(route('login'));

        $this->loginAsAdmin();

        $this->get($edit_route)
            ->assertStatus(200)
            ->assertViewIs('profiles.edit')
            ->assertSeeTextInOrder(['Edit', $profile->name, 'Patents'])
            ->assertSee($profile_data->data['title']);

        $new_data = ProfileData::factory()->patents()->make()->data;
        $us = $new_data['members']['patent_1'];
        $jp = $new_data['members']['patent_2'];

        $update_route = route('profiles.update', ['profile' => $profile, 'section' => 'patents']);

        $response = $this->followingRedirects()->post($update_route, [
            'data' => [
                $profile_data->id => [
                    'id'   => $profile_data->id,
                    'data' => $new_data,
                ],
            ],
            'public' => 1,
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertStatus(200)
            ->assertViewIs('profiles.show')
            ->assertSee('Profile updated.')
            ->assertSeeText($new_data['title'])
            ->assertSeeText($us['patent_no'])
            ->assertSeeText($us['published_date'])  // m/d/Y survives the round-trip
            ->assertSeeText($jp['patent_no'])
            ->assertSeeText($jp['published_date']);

        $profile_data->refresh();
        $this->assertSame($us['published_date'], $profile_data->data['members']['patent_1']['published_date']);
        $this->assertSame('US', $profile_data->data['members']['patent_1']['jurisdiction']);
        $this->assertSame('JP', $profile_data->data['members']['patent_2']['jurisdiction']);
    }

    /**
     * The jurisdiction must be one of the valid countries.
     */
    public function testProfilePatentsEditFailsWithoutJurisdiction(): void
    {
        $profile = Profile::factory()->create();
        $profile_data = ProfileData::factory()->patents()->create(['profile_id' => $profile->id]);

        $this->loginAsAdmin();

        $bad_data = ProfileData::factory()->patents()->make()->data;
        $bad_data['members']['patent_1']['jurisdiction'] = ''; // the invalid field

        $update_route = route('profiles.update', ['profile' => $profile, 'section' => 'patents']);

        $this->post($update_route, [
            'data' => [
                $profile_data->id => [
                    'id'   => $profile_data->id,
                    'data' => $bad_data,
                ],
            ],
            'public' => 1,
        ])->assertSessionHasErrors("data.{$profile_data->id}.data.members.patent_1.jurisdiction");
    }

    /**
     * Verify the status whitelist: the rules allow only published/pending/expired.
     */
    public function testProfilePatentsEditRejectsOtherStatus(): void
    {
        $profile = Profile::factory()->create();
        $profile_data = ProfileData::factory()->patents()->create(['profile_id' => $profile->id]);

        $this->loginAsAdmin();

        $data_with_wrong_status = ProfileData::factory()->patents()->make()->data;
        $data_with_wrong_status['members']['patent_1']['status'] = 'other'; // not in the whitelist

        $update_route = route('profiles.update', ['profile' => $profile, 'section' => 'patents']);

        $this->post($update_route, [
            'data' => [
                $profile_data->id => [
                    'id'   => $profile_data->id,
                    'data' => $data_with_wrong_status,
                ],
            ],
            'public' => 1,
        ])->assertSessionHasErrors("data.{$profile_data->id}.data.members.patent_1.status");
    }
}