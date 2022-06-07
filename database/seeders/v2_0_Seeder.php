<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class v2_0_Seeder extends Seeder
{
    /**
     * Updates data for release v2.0
     */
    public function run(): void
    {
        $this->populateMediaUUIDs();
    }

    /**
     * Populates media records with unique UUIDs
     */
    public function populateMediaUUIDs(): void
    {
        Media::cursor()->each(
            fn (Media $media) => $media->update(['uuid' => Str::uuid()])
        );
    }
}
