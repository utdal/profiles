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

    /**
     * This belongs to one user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Bookmark belongs to a given user
     * 
     * @param User $user
     * @return bool
     */
    public function ownerIs(User $user): bool
    {
        return $this->user_id === $user->id;
    }
}
