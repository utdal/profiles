<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\Models\Media;

class Setting extends Model implements HasMedia
{
    use HasMediaTrait;

    /** @var array The attributes that are mass-assignable */
    protected $fillable = [
        'name',
        'value',
    ];

    /**
     * Registers Setting media collections
     *
     * @return void
     */
    public function registerMediaCollections()
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
     * @return Spatie\MediaLibrary\Conversion\Conversion
     */
    protected function registerImageThumbnails(Media $media = null, $name, $width, $height = null)
    {
        if (!$height) {
            $height = $width;
        }
        return $this->addMediaConversion($name)->width($width)->height($height)->crop(Manipulations::CROP_TOP, $width, $height)->performOnCollections('logo');
    }

}
