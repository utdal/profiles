<?php

namespace App\Conversions;

use Illuminate\Support\Collection;
use Imagick;
use ImagickPixel;
use Spatie\MediaLibrary\Conversions\Conversion;
use Spatie\MediaLibrary\Conversions\ImageGenerators\ImageGenerator;

class Svg extends ImageGenerator
{
    /**
     * Converts SVG to PNG, preserving transparency
     */
    public function convert(string $file, Conversion $conversion = null): string
    {
        $imageFile = pathinfo($file, PATHINFO_DIRNAME) . '/' . pathinfo($file, PATHINFO_FILENAME) . '.png';

        $image = new Imagick();
        $image->setBackgroundColor(new ImagickPixel('transparent')); // set bg BEFORE reading file to preserve transparency
        $image->readImage($file);
        $image->setImageFormat('png');

        file_put_contents($imageFile, $image->getImageBlob());

        return $imageFile;
    }

    public function requirementsAreInstalled(): bool
    {
        return class_exists(\Imagick::class);
    }

    public function supportedExtensions(): Collection
    {
        return collect(['svg']);
    }

    public function supportedMimeTypes(): Collection
    {
        return collect(['image/svg+xml']);
    }
}
