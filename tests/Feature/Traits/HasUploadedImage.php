<?php

namespace Tests\Feature\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait HasUploadedImage
{
    /**
     * Mock an uploaded image file
     *
     * @return \Illuminate\Http\Testing\File
     */
    protected function mockUploadedImage()
    {
        Storage::fake('images');

        return UploadedFile::fake()->image('fake_image.jpg', 300, 300);
    }
}
