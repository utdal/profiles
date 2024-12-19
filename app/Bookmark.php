<?php

namespace App;

use App\Profile;
use App\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
     */
    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class, 'userable_id');
    }

    /**
     * Bookmark(s) belong to one User (many-to-one)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
