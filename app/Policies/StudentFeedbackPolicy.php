<?php

namespace App\Policies;

use App\Student;
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
     * @return void|bool
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
        return $user->userOrDelegatorhasRole(['faculty', 'students_admin']);
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
        return $this->viewAny($user) || $user->owns($studentFeedback, true) || $user->owns($studentFeedback->student, true);
    }

    /**
     * Determine whether the user can create feedback.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user, Student $student)
    {
        $assoc_profile_can_add_feedback = $student->isAssociatedToUserProfiles($user);
        
        return $user->userOrDelegatorhasRole(['faculty', 'students_admin']) || $assoc_profile_can_add_feedback;
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
        return $user->userOrDelegatorhasRole(['students_admin']) || $user->owns($studentFeedback, true);
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
        return $user->userOrDelegatorhasRole(['students_admin']) || $user->owns($studentFeedback, true);
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
