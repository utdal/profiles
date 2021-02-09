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
     * Test the basic API.
     *
     * @return void
     */
    public function testApi()
    {
        $this->seed();

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

        $profiles = [];
        $profile_data = [];
        $number_of_profiles = 10;

        for ($i=1; $i <= $number_of_profiles; $i++) { 
            $profiles[$i] = factory(Profile::class)->create();
            // basic info is always created with a profile
            $profile_data[$profiles[$i]->id] = factory(ProfileData::class)->create([
                'profile_id' => $profiles[$i]->id,
            ]);
        }

        Cache::flush();

        $response = $this->get(route('api.index'));

        $response
            ->assertStatus(200)
            ->assertJsonCount(10, 'profile')
            ->assertJson([
                'count' => 10,
            ]);

        for ($i = 1; $i <= $number_of_profiles; $i++) {
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
            ->assertJsonFragment($this->profileInfoJsonFragment($profile_data[$profiles[1]->id]))
            ->assertJsonFragment($this->profileJsonFragment($profiles[2]))
            ->assertJsonFragment($this->profileInfoJsonFragment($profile_data[$profiles[2]->id]));

        ////////////////////
        // Single profile //
        ////////////////////

        // profiles.test/api/v1/person3slug
        $response = $this->get(route('api.show', ['profile' => $profiles[3]]));

        $response
            ->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJson([
                'profile' => $this->profileJsonFragment($profiles[3]),
            ])
            ->assertJsonFragment($this->profileInfoJsonFragment($profile_data[$profiles[3]->id]));
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
                'email' => $profile_datum->email,
                'phone' => $profile_datum->phone,
                'title' => $profile_datum->title,
                'secondary_title' => $profile_datum->secondary_title,
                'tertiary_title' => $profile_datum->tertiary_title,
                'location' => $profile_datum->location,
            ],
        ];
    }
}
