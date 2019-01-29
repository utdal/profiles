<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Runs before any other authorization checks
     *
     * @param \App\User $user
     * @param string $ability
     * @return bool
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
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAdminIndex(User $user)
    {
        return $user->hasRole(['profiles_editor', 'school_profiles_editor', 'department_profiles_editor']);
    }
    
    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function view(User $user, User $model)
    {
        return $user->owns($model) || $user->hasRole(['profiles_editor', 'school_profiles_editor', 'department_profiles_editor']);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasRole(['profiles_editor', 'school_profiles_editor', 'department_profiles_editor']);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function update(User $user, User $model)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function delete(User $user, User $model)
    {
        return false;
    }
}
