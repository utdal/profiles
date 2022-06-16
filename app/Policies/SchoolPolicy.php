<?php

namespace App\Policies;

use App\School;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SchoolPolicy
{
    use HandlesAuthorization;

    /**
     * Runs before any other authorization checks
     *
     * @param \App\User $user
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
     * Determine whether the user can view the index.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $this->viewAdminIndex($user);
    }

    /**
     * Determine whether the user can view the admin index.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAdminIndex(User $user)
    {
        return false;
    }
    
    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\School  $school
     * @return mixed
     */
    public function view(User $user, School $school)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\School  $school
     * @return mixed
     */
    public function update(User $user, School $school)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\School  $school
     * @return mixed
     */
    public function delete(User $user, School $school)
    {
        return false;
    }
}
