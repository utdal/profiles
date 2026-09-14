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
     * Determine whether the user can view the index.
     *
     * @param  User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can view the setting.
     *
     * @param  User  $user
     * @param  Setting|null  $setting
     * @return mixed
     */
    public function view(User $user, ?Setting $setting = null)
    {
        return false;
    }

    /**
     * Determine whether the user can create settings.
     *
     * @param  User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the setting.
     *
     * @param  User  $user
     * @param  Setting|null  $setting
     * @return mixed
     */
    public function update(User $user, ?Setting $setting = null)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the setting.
     *
     * @param  User  $user
     * @param  Setting|null  $setting
     * @return mixed
     */
    public function delete(User $user, ?Setting $setting = null)
    {
        return false;
    }
}
