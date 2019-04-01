<?php

namespace App\Policies;

use App\User;
use App\Setting;
use Illuminate\Auth\Access\HandlesAuthorization;

class SettingPolicy
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
     * Determine whether the user can view the setting.
     *
     * @param  \App\User  $user
     * @param  \App\Setting  $setting
     * @return mixed
     */
    public function view(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can create settings.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the setting.
     *
     * @param  \App\User  $user
     * @param  \App\Setting  $setting
     * @return mixed
     */
    public function update(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the setting.
     *
     * @param  \App\User  $user
     * @param  \App\Setting  $setting
     * @return mixed
     */
    public function delete(User $user)
    {
        return false;
    }
}
