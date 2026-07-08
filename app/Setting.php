<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Enums\CropPosition;
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
    public function registerMediaConversions(?Media $media = null): void
    {
        $this->registerImageThumbnails('thumb', 150);
        $this->registerImageThumbnails('medium', 450);
        $this->registerImageThumbnails('large', 1800, 1200);
    }

    /**
     * Registers image thumbnails.
     *
     * @param  string     $name       Name of the thumbnail
     * @param  int        $width      Max width dimension in pixels
     * @param  int        $height     Max height dimension in pixels
     * @return void
     */
    protected function registerImageThumbnails(string $name, int $width, ?int $height = null): void
    {
        if (!$height) {
            $height = $width;
        }

        $this->addMediaConversion($name)
            ->width($width)
            ->height($height)
            ->crop($width, $height, CropPosition::Top)
            ->format('png')
            ->performOnCollections('logo');
    }

}
