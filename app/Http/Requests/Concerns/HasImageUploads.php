<?php

namespace App\Http\Requests\Concerns;

trait HasImageUploads
{
    public function uploadedImageRules(): string
    {
        $max_filesize = $this->maxFilesize() * 1000;
        $allowed_mimes = implode(',', $this->supportedMimes());

        return "mimes:$allowed_mimes|min:1|max:$max_filesize";
    }

    public function uploadedImageMessages(string $rule): string
    {
        if ($rule === 'max') {
            return "The :attribute may not be greater than {$this->maxFilesize()} MB.";
        }

        return '';
    }

    /**
     * Configured max image upload file size
     *
     * @return int in MB
     */
    public function maxFilesize()
    {
        return config('medialibrary.max_file_size') / (1024 * 1024);
    }

    /**
     * Determine the supported uploaded image types
     * 
     * @link http://image.intervention.io/getting_started/formats
     *
     * @return array
     */
    public function supportedMimes(): array
    {
        $image_driver = config('medialibrary.image_driver', 'gd');
        $mimes = [];

        if ($image_driver === 'gd' && extension_loaded('gd') && function_exists('imagetypes')) {
            $image_types = imagetypes();

            if ($image_types & \IMG_JPG) {
                $mimes[] = 'jpeg';
            }
            if ($image_types & \IMG_PNG) {
                $mimes[] = 'png';
            }
            if ($image_types & \IMG_GIF) {
                $mimes[] = 'gif';
            }
            if ($image_types & \IMG_WEBP) {
                $mimes[] = 'webp';
            }
        } elseif ($image_driver === 'imagick' && extension_loaded('imagick') && class_exists('Imagick')) {
            if (\Imagick::queryFormats('JPEG')) {
                $mimes[] = 'jpeg';
            }
            if (\Imagick::queryFormats('PNG')) {
                $mimes[] = 'png';
            }
            if (\Imagick::queryFormats('GIF')) {
                $mimes[] = 'gif';
            }
            if (\Imagick::queryFormats('BMP')) {
                $mimes[] = 'bmp';
            }
            if (\Imagick::queryFormats('SVG')) {
                $mimes[] = 'svg';
            }
            if (\Imagick::queryFormats('WEBP')) {
                $mimes[] = 'webp';
            }
        }

        // default: GD driver
        return $mimes;
    }

}
