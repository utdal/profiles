<?php

namespace App\Http\Controllers;

use App\Profile;
use App\Student;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AppController extends Controller
{
    /**
     * Display the App Homepage.
     */
    public function index(): View|ViewContract
    {
        return view('home');
    }

    /**
     * Display the FAQ page.
     */
    public function faq(): View|ViewContract
    {
        return view('faq');
    }

    public function requestDownload(User $user, $ability, $model,string $token)
    {
        $this->authorizePdfAccess($ability, $model);

        return view('initiate-download', compact('token'));
    }

    public function download(Request $request, User $user, $ability, $model)
    {
        $this->authorizePdfAccess($ability, $model);

        $path = $request->query('path');
        $name = $request->query('filename', 'document.pdf');

        abort_unless(is_string($path), 403);
        abort_unless(Storage::exists($path), 404);

        $absolute = Storage::path($path);

        return response()->download($absolute, $name)->deleteFileAfterSend(true);
    }

    public function authorizePdfAccess(string $ability, $model)
    {
        if (!in_array($model, [Student::class, Profile::class])) {
            abort(400, 'Invalid model class.');
        }

        $this->authorize($ability, $model);
    }
}
