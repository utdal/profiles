<?php

namespace Database\Seeders;

use App\Profile;
use Illuminate\Database\Seeder;

class v1_3_Seeder extends Seeder
{
    /**
     * Profiles v1.3 changed the profile display name to read from
     * the `full_name` attribute instead of `first_name last_name`.
     * 
     * Run this seeder to ensure existing profiles have the same
     * display name as in previous versions.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('=== Profiles v1.3 Data Updater ===');

        $this->command->info('Checking profile display name consistency...');

        Profile::all()->each(function($profile) {
            $previous_version_name = "{$profile->first_name} {$profile->last_name}";

            if ($profile->full_name !== $previous_version_name) {
                $this->command->info("Updating profile {$profile->id} full_name from \"{$profile->full_name}\" to \"{$previous_version_name}\"");
                $profile->update(['full_name' => $previous_version_name]);
            }
        });

        $this->command->info('Done!');
    }
}
