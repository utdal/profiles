<?php

namespace App\Policies;

use App\Profile;
use App\Student;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProfileStudentPolicy
{
    use HandlesAuthorization;

    /**
     * Runs before any other authorization checks
     *
     * @param \App\User $user
     * @param string $ability
     * @return void|bool
     */
    public function before(User $user, $ability)
    {
        if ($user->hasRole('site_admin')) {
            return true;
        }
    }

    /**
     * Determine whether the user can view any profile-specific students.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewForAnyProfile(User $user)
    {
        return $user->hasRole('students_admin');
    }

    /**
     * Determine whether the user can view profile-specific students.
     *
     * @param  \App\User  $user
     * @param  \App\Profile  $profile
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewForProfile(User $user, Profile $profile)
    {
        return $user->owns($profile, true) || $this->viewForAnyProfile($user);
    }

    /**
     * Determine whether the user can create profile-student associations.
     *
     * @param  \App\User  $user
     * @param  \App\Student $student
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user, Student $student)
    {
        // Students can express their own interest in faculty profiles
        return $user->can('update', $student);
    }

    /**
     * Determine whether the user can update the profile-student status.
     *
     * @param  \App\User  $user
     * @param  \App\Profile  $profile
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Profile $profile)
    {
        return $user->owns($profile, true);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Student  $student
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Student $student)
    {
        // Students can remove their own interest from faculty profiles
        return $user->can('update', $student);
    }
}
