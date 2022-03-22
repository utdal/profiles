<?php

namespace App\Http\Controllers;

use App\Profile;
use App\Student;
use Illuminate\Http\Request;

class ProfileStudentsController extends Controller
{
    /**
     * Show students associated with a profile.
     *
     * @return \Illuminate\View\View
     */
    public function show(Profile $profile)
    {
        return view('students.profile-students', [
            'profile' => $profile,
        ]);
    }
}
