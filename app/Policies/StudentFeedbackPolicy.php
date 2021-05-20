<?php

namespace App\Policies;

use App\StudentFeedback;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StudentFeedbackPolicy
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
     * Determine whether the user can view any feedback.
     *
     * @see StudentPolicy::viewFeedback() for the policy to view all feedback on a particular student
     * 
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasRole(['faculty', 'students_admin']);
    }

    /**
     * Determine whether the user can view the feedback.
     *
     * @param  \App\User  $user
     * @param  \App\StudentFeedback  $studentFeedback
     * @return mixed
     */
    public function view(User $user, StudentFeedback $studentFeedback)
    {
        return $this->viewAny($user) || $user->owns($studentFeedback) || $user->owns($studentFeedback->student);
    }

    /**
     * Determine whether the user can create feedback.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasRole(['faculty', 'students_admin']);
    }

    /**
     * Determine whether the user can update the feedback.
     *
     * @param  \App\User  $user
     * @param  \App\StudentFeedback  $studentFeedback
     * @return mixed
     */
    public function update(User $user, StudentFeedback $studentFeedback)
    {
        return $user->hasRole(['students_admin']) || $user->owns($studentFeedback);;
    }

    /**
     * Determine whether the user can delete the feedback.
     *
     * @param  \App\User  $user
     * @param  \App\StudentFeedback  $studentFeedback
     * @return mixed
     */
    public function delete(User $user, StudentFeedback $studentFeedback)
    {
        return $user->hasRole(['students_admin']) || $user->owns($studentFeedback);
    }

    /**
     * Determine whether the user can restore the feedback.
     *
     * @param  \App\User  $user
     * @param  \App\StudentFeedback  $studentFeedback
     * @return mixed
     */
    public function restore(User $user, StudentFeedback $studentFeedback)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the feedback.
     *
     * @param  \App\User  $user
     * @param  \App\StudentFeedback  $studentFeedback
     * @return mixed
     */
    public function forceDelete(User $user, StudentFeedback $studentFeedback)
    {
        return false;
    }
}
