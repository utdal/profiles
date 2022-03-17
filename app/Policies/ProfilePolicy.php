<?php

namespace App\Policies;

use App\User;
use App\Profile;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProfilePolicy
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
        if ($user->hasRole(['site_admin', 'profiles_editor'])) {
            return true;
        }
    }

    /**
     * Checks to see if the user is a school editor for the profile
     *
     * @param User $user
     * @param Profile $profile
     * @return bool
     */
    protected function checkSchoolEditor(User $user, Profile $profile)
    {
        return $user->hasRoleOption('school_profiles_editor', 'schools', $profile->user->school_id ?? -1);
    }

    /**
     * Checks to see if the user is a department editor for the profile
     *
     * @param User $user
     * @param Profile $profile
     * @return bool
     */
    protected function checkDepartmentEditor(User $user, Profile $profile)
    {
        return $user->hasRoleOption('department_profiles_editor', 'departments', $profile->user->department ?? 'none');
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
     * Determine whether the user can view the admin table of Profiles.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAdminIndex(User $user)
    {
        return $user->hasRole(['profiles_editor', 'school_profiles_editor', 'department_profiles_editor']);
    }

    /**
     * Determine whether the user can view the profile.
     *
     * @param  \App\User  $user
     * @param  \App\Profile  $profile
     * @return mixed
     */
    public function view(User $user, Profile $profile)
    {
        return true;
    }
    
    /**
     * Determine whether the user can administer profiles.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function administer(User $user)
    {
        return $user->hasRole(['profiles_editor', 'school_profiles_editor', 'department_profiles_editor']);
    }

    /**
     * Determine whether the user can create profiles.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasRole(['profiles_editor', 'school_profiles_editor', 'department_profiles_editor']);
    }

    /**
     * Determine whether the user can create their own profiles.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function createOwn(User $user)
    {
        // Faculty can create a profile if they don't already have one
        return $user->exists() && $user->hasRole('faculty') && !$user->profiles()->exists();
    }

    /**
     * Determine whether the user can update the profile.
     *
     * @param  \App\User  $user
     * @param  \App\Profile  $profile
     * @return mixed
     */
    public function update(User $user, Profile $profile)
    {
        return $user->owns($profile, true) ||
               $this->checkSchoolEditor($user, $profile) ||
               $this->checkDepartmentEditor($user, $profile);
    }

    /**
     * Determine whether the user can delete the profile.
     *
     * @param  \App\User  $user
     * @param  \App\Profile  $profile
     * @return mixed
     */
    public function delete(User $user, Profile $profile)
    {
        return $user->owns($profile);
    }
}
