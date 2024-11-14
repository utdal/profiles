<?php

namespace App;

use App\Profile;
use Illuminate\Database\Eloquent\Relations\MorphPivot;

class Bookmark extends MorphPivot
{
    /** @var bool Indicates if the IDs are auto-incrementing. */
    public $incrementing = true;

    /** @var bool Indicates if this model has timestamps. */
    public $timestamps = true;

    /** @var string The database table for this model. */
    public $table = 'bookmarks';

    //////////////
    // Relations//
    //////////////

    /**
     * Bookmark has one Profile (one-to-one)
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function profile()
    {
        return $this->belongsTo(Profile::class, 'userable_id');
    }

}
