<?php

namespace Tests\Feature\Traits;

use App\Role;
use App\User;
use Illuminate\Support\Facades\Auth;

trait LoginWithRole
{
    /**
     * Login as a user with a specific role.
     * 
     * @param string $role_name
     * @param User $user (optional) the User to whom to assign the role and login as (default: a random new user)
     * @return User|null
     */
    protected function loginAsUserWithRole($role_name, $user = null)
    {
        if ($role_name === 'guest') {
            return $this->actingAsGuest();
        }

        if ($user === null) {
            $user = User::factory()->create();
            $user->detachRoles();
        }

        if ($role_name && Role::whereName($role_name)->exists()) {
            $user->attachRole(Role::whereName($role_name)->firstOrFail());
        }

        $this->actingAs($user);

        return $user;
    }

    /**
     * Act as a guest (logout)
     */
    protected function actingAsGuest(): void
    {
        Auth::logout();
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

    /**
     * Data provider for various roles
     */
    public static function userRolesProvider(): array
    {
        return [
            ['guest'],
            ['member'],
            ['faculty'],
            ['student'],
            ['students_admin'],
            ['site_admin'],
            ['data_owner'],
        ];
    }
}
