<?php

namespace App\Http\Controllers;

use App\Profile;
use App\Student;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Database\Eloquent\Model;
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

    public function requestDownload(User $user, string $token)
    {
        return view('initiate-download', compact('token'));
    }

}
