<?php

namespace App\Http\Controllers;

use App\Profile;
use Illuminate\Http\Request;

class ProfileStudentsController extends Controller
{
    /**
     * Controller constructor. Middleware can be defined here.
     */
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware('can:viewStudents,profile')->only('show');
    }

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
