<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;
use Spatie\MediaLibrary\Media;

class Setting extends Model implements HasMediaConversions
{

    use HasMediaTrait;

    /** @var array The attributes that are mass-assignable */
    protected $fillable = [
        'name',
        'value',
    ];


    /**
     * Registers media conversions.
     *
     * @param  Media|null $media
     */
    public function registerMediaConversions(Media $media = null)
    {
        $this->registerImageThumbnails($media, 'thumb', 150);
        $this->registerImageThumbnails($media, 'medium', 450);
        $this->registerImageThumbnails($media, 'large', 1800, 1200, '*');
    }

    /**
     * Registers image thumbnails.
     *
     * @param  Media|null $media
     * @param  string     $name       Name of the thumbnail
     * @param  int        $size       Max dimension in pixels
     * @param  string     $collection Name of the collection for the thumbnails
     * @return Spatie\MediaLibrary\Conversion\Conversion
     */
    protected function registerImageThumbnails(Media $media = null, $name, $width, $height = null, $collection = 'settings')
    {
        if(!$height) {
            $height = $width;
        }
        return $this->addMediaConversion($name)->width($width)->height($height)->crop(Manipulations::CROP_TOP, $width, $height)->performOnCollections($collection);
    }

    public function processImage($new_image, $collection = 'settings'){
        if ($new_image) {
            $this->clearMediaCollection($collection);
            $this->addMedia($new_image)->toMediaCollection($collection);
            $message = 'Settings image has been updated.';
        } else {
            $message = 'Cannot update settings image.';
        }

        return $message;
    }

    /**
     * Get the full image URL. ($this->full_image_url)
     *
     * @return string
     */
    public function getFullImageUrlAttribute()
    {
        return url($this->getFirstMediaUrl('settings') ?: '/img/default.png');
    }
}
