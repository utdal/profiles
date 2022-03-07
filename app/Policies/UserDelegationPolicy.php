<?php

namespace App\Policies;

use App\User;
use App\UserDelegation;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserDelegationPolicy
{
    use HandlesAuthorization;

    /**
     * Runs before any other authorization checks
     *
     * @param \App\User $user
     * @param string $ability
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function before($user, $ability)
    {
        if ($user->hasRole('site_admin')) {
            return true;
        }
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAdminIndex(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $this->viewAdminIndex($user);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\UserDelegation  $userDelegation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, User $userDelegation)
    {
        return $userDelegation->delegatorIs($user);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\UserDelegation  $userDelegation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewForDelegator(User $user, User $delegator)
    {
        return $user->is($delegator);
    }

    /**
     * Determine whether the user can create delegations for the given delegator.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user, User $delegator)
    {
        return $user->is($delegator);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\UserDelegation  $userDelegation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, UserDelegation $userDelegation)
    {
        return $userDelegation->delegatorIs($user);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\UserDelegation  $userDelegation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, UserDelegation $userDelegation)
    {
        return $userDelegation->delegatorIs($user);
    }
}
