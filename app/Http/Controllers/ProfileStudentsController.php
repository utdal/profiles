<?php

namespace App\Http\Controllers;

use App\Profile;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\Request;
use Illuminate\View\View;

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
     * Show student applications associated with a profile.
     */
    public function show(Profile $profile): View|ViewContract
    {
        return view('students.profile-students', [
            'profile' => $profile,
        ]);
    }
}
