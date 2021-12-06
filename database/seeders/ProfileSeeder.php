<?php

namespace Database\Seeders;

use App\Profile;
use App\ProfileData;
use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create a random User, Profile, and ProfileData

        $profile = Profile::factory()->create();

        $profile_data = ProfileData::factory()->make();

        $profile_data->profile_id = $profile->id;
        $profile_data->setAttribute('data->email', $profile->user->email);
        $profile_data->setAttribute('data->title', $profile->user->title);

        $profile_data->save();
    }
}
