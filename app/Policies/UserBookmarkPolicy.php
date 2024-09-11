<?php

namespace App\Policies;

use App\Bookmark;
use App\User;

class UserBookmarkPolicy
{
    // use HandlesAuthorization;

    /**
     * Runs before any other authorization checks
     *
     * @param User $user
     * @param string $ability
     * @return void|bool
     */
    public function before($user, $ability)
    {
        if ($user->hasRole('site_admin')) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the owner's bookmarks.
     *
     * @param  User  $user
     * @param  User  $owner
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewBookmarks(User $user, User $owner)
    {
        return $user->is($owner);
    }

    /**
     * Determine whether the user can create bookmarks.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the bookmark.
     *
     * @param  \App\User  $user
     * @param  \App\Bookmark $bookmark
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Bookmark $bookmark)
    {
        return $user->owns($bookmark);
    }
}
