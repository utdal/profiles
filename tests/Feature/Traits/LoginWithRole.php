<?php

namespace Tests\Feature\Traits;

use App\Role;
use App\User;

trait LoginWithRole
{
    /**
     * Login as a user with a specific role.
     * 
     * @param string $role_name
     * @param User $user (optional) the User to whom to assign the role and login as (default: a random new user)
     * @return User
     */
    protected function loginAsUserWithRole($role_name, $user = null)
    {
        if ($user === null) {
            $user = factory(User::class)->create();
            $user->detachRoles();
        }

        if ($role_name) {
            $user->attachRole(Role::whereName($role_name)->firstOrFail());
        }

        $this->actingAs($user);

        return $user;
    }

    /**
     * Login as a normal user.
     *
     * @param User $user (optional) the User to make a member and login as (default: a random new user).
     * @return User
     */
    protected function loginAsUser($user = null)
    {
        return $this->loginAsUserWithRole('', $user);
    }

    /**
     * Login as a site administrator user.
     *
     * @param User $user (optional) the User to make an admin and login as (default: a random new user).
     * @return User
     */
    protected function loginAsAdmin($user = null)
    {
        return $this->loginAsUserWithRole('site_admin', $user);
    }
}
