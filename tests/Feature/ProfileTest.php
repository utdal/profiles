<?php

namespace Tests\Feature;

use App\Profile;
use App\ProfileData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Traits\HasJson;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use HasJson;
    use RefreshDatabase;

    /**
     * A basic feature test example.
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
        $this->get(route('profiles.index'))->assertStatus(200);
        $this->get(route('profiles.show', ['profile' => $profile]))
            ->assertStatus(200)
            ->assertSee($profile->name);
    }
}
