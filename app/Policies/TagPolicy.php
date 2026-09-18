<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Spatie\Tags\Tag;

class TagPolicy
{
    use HandlesAuthorization;

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
     * Determine whether the user can view the model.
     *
     * @param  User  $user
     * @return mixed
     */
    public function viewAdminIndex(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can create tags.
     *
     * @param  User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the tag.
     *
     * @param  User  $user
     * @param  Tag|null  $tag
     * @return mixed
     */
    public function update(User $user, ?Tag $tag = null)
    {
        return false;
    }

    /**
     * Determine whether the user can update the tag.
     *
     * @param  User  $user
     * @param  Tag  $tag
     * @return mixed
     */
    public function updateTag(User $user, ?Tag $tag = null)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the tag.
     *
     * @param  User  $user
     * @param  Tag  $tag
     * @return mixed
     */
    public function delete(User $user, ?Tag $tag = null)
    {
        return false;
    }
}
