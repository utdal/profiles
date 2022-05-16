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

        $profile = Profile::factory()->hasData()->create();

        // Echo the result

        $this->command->line(
            "✅ Created 🪪 Profile (id: {$profile->id}, full_name: {$profile->full_name}), 
            👤 User (id: {$profile->user->id}, name: {$profile->user->name}), 
            💾 ProfileData (id: {$profile->data->first()->id}, type: {$profile->data->first()->type})"
        );
    }
}
