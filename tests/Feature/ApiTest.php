<?php

namespace Tests\Feature;

use App\Profile;
use App\ProfileData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

/**
 * @group api
 */
class ApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Indicates whether the default seeder should run before each test.
     *
     * @var bool
     */
    protected $seed = true;

    /**
     * Test the basic API.
     *
     * @return void
     */
    public function testApi()
    {
        //////////////////////
        // Without profiles //
        //////////////////////

        // profiles.test/api/v1
        $response = $this->get(route('api.index'));

        $response->assertStatus(200)->assertJson([
            'count' => 0,
            'profile' => [],
        ]);

        //////////////////
        // With profiles//
        //////////////////

        /** @var int Number of profiles (minimum 3 for testing) */
        $number_of_profiles = 10;

        /** @var \Illuminate\Database\Eloquent\Collection */
        $profiles = Profile::factory()->hasData()->count($number_of_profiles)->create();

        Cache::flush();

        $response = $this->get(route('api.index'));

        $response
            ->assertStatus(200)
            ->assertJsonCount($number_of_profiles, 'profile')
            ->assertJson([
                'count' => $number_of_profiles,
            ]);

        for ($i = 0; $i < $number_of_profiles; $i++) {
            $response->assertJsonFragment($this->profileJsonFragment($profiles[$i]));
        }

        ////////////////////////////
        // All profiles with data //
        ////////////////////////////

        // profiles.test/api/v1?with_data=1
        $response = $this->get(route('api.index', ['with_data' => 1]));

        $response
            ->assertStatus(400)
            ->assertJson([
                'error' => "Please use a filter when pulling data.",
            ]);

        ////////////////////////////////
        // Certain profiles with data //
        ////////////////////////////////

        // profiles.test/api/v1?with_data=1&person=person1slug;person2slug
        $response = $this->get(route('api.index', [
            'with_data' => 1,
            'person' => $profiles[1]->slug . ';' . $profiles[2]->slug,
        ]));

        $response
            ->assertStatus(200)
            ->assertJsonCount(2, 'profile')
            ->assertJson([
                'count' => 2,
            ])
            ->assertJsonFragment($this->profileJsonFragment($profiles[1]))
            ->assertJsonFragment($this->profileInfoJsonFragment($profiles[1]->data->first()))
            ->assertJsonFragment($this->profileJsonFragment($profiles[2]))
            ->assertJsonFragment($this->profileInfoJsonFragment($profiles[2]->data->first()));

        ////////////////////
        // Single profile //
        ////////////////////

        // profiles.test/api/v1/person3slug
        $response = $this->get(route('api.show', ['profile' => $profiles[0]]));

        $response
            ->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJson([
                'profile' => $this->profileJsonFragment($profiles[0]),
            ])
            ->assertJsonFragment($this->profileInfoJsonFragment($profiles[0]->data->first()));
    }

    /**
     * Get the proper Profile JSON fragment
     *
     * @param \App\Profile $profile
     * @return array
     */
    protected function profileJsonFragment($profile): array
    {
        return [
            'id' => $profile->id,
            'first_name' => $profile->first_name,
            'last_name' => $profile->last_name,
            'name' => $profile->name,
            'slug' => $profile->slug,
            'public' => true,
            'url' => $profile->url,
        ];
    }

    /**
     * Get the proper ProfileData information JSON fragment
     *
     * @param \App\ProfileData $profile_datum
     * @return array
     */
    protected function profileInfoJsonFragment($profile_datum): array
    {
        return [
            'type' => 'information',
            'profile_id' => $profile_datum->profile_id,
            'sort_order' => $profile_datum->sort_order,
            'data' => [
                'academic_analytics_id' => $profile_datum->academic_analytics_id,
                'email' => $profile_datum->email,
                'phone' => $profile_datum->phone,
                'title' => $profile_datum->title,
                'secondary_title' => $profile_datum->secondary_title,
                'tertiary_title' => $profile_datum->tertiary_title,
                'location' => $profile_datum->location,
                'url' => $profile_datum->url,
                'url_name' => $profile_datum->url_name,
            ],
        ];
    }
}
