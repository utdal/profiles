<?php

namespace App\Http\Controllers;

use App\Profile;
use App\User;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\ValidateSignature;
use Illuminate\Support\Facades\Storage;
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

        // $this->middleware('can:downloadStudents,user')->only(['requestDownload']);
        // $this->middleware('can:downloadStudents,profile')->only(['requestDownload', 'downloadPdf']);
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
    
    public function requestDownload(User $user, string $token)
    {
        return $this->initiatePdfDownload($token);
    }

    public function downloadPdf(Request $request)
    {
        $path = $request->query('path');
        $name = $request->query('filename', 'document.pdf');

        return $this->downloadPdfFile($path, $name);
    }
}
