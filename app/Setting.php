<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Setting extends Model implements HasMedia
{
    use InteractsWithMedia;

    /** @var array The attributes that are mass-assignable */
    protected $fillable = [
        'name',
        'value',
    ];

    /**
     * Registers Setting media collections
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')->singleFile();
        $this->addMediaCollection('favicon')->singleFile();
        $this->addMediaCollection('student_info_image')->singleFile();
    }

    /**
     * Registers media conversions.
     *
     * @param  Media|null $media
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->registerImageThumbnails($media, 'thumb', 150);
        $this->registerImageThumbnails($media, 'medium', 450);
        $this->registerImageThumbnails($media, 'large', 1800, 1200);
    }

    /**
     * Registers image thumbnails.
     *
     * @param  Media|null $media
     * @param  string     $name       Name of the thumbnail
     * @param  int        $size       Max dimension in pixels
     * @return void
     */
    protected function registerImageThumbnails(Media $media = null, $name, $width, $height = null): void
    {
        if (!$height) {
            $height = $width;
        }

        $this->addMediaConversion($name)
            ->width($width)
            ->height($height)
            ->crop(Manipulations::CROP_TOP, $width, $height)
            ->performOnCollections('logo');
    }

}
