<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View as ViewContract;
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
}
