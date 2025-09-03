<?php

namespace App\Http\Controllers;

use App\Profile;
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

        $this->middleware('can:downloadStudents,profile')->only(['initiateDownload']);
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
    
    public function initiateDownload(Profile $profile)
    {
        $sessionKey = 'download_token_' . auth()->user()->id;

        if (!session()->has($sessionKey)) {
            abort(403);
        }

        $token = session()->pull($sessionKey);
        
        return view('initiate-download', compact('profile', 'token'));
    }

    public function downloadPdf(Request $request)
    {
        $path = $request->query('path');
        $name = $request->query('name', 'document.pdf');

        abort_unless(is_string($path), 403);
        abort_unless(Storage::exists($path), 404);

        $absolute = Storage::path($path);

        return response()->download($absolute, $name)->deleteFileAfterSend(true);
    }
}
