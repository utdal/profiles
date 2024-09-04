<?php

namespace App\Policies;

use App\Bookmark;
use App\User;

class BookmarkPolicy
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
     * Determine whether a user can view their their own or another's user's bookmarks
     *
     * @param  User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewUserIndex(User $user)
    {
        $bookmarks_owner_requested = request()->route()->user;

        foreach ($bookmarks_owner_requested->bookmarks as $bookmark) {
            if(!$this->view($user, $bookmark))
            {
                return false;
            }
        }
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  User  $user
     * @param  Bookmark  $bookmark
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Bookmark $bookmark)
    {
        return $user->owns($bookmark) || $user->hasRole(['profiles_editor', 'school_profiles_editor', 'department_profiles_editor']);
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
        return $user->owns($bookmark) || $user->hasRole('profiles_editor');
    }
}
