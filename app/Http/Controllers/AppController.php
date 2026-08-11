<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Contracts\View\View;

class AppController extends Controller
{
    /**
     * Display the App Homepage.
     */
    public function index(): View|ViewFactory
    {
        return view('home');
    }

    /**
     * Display the FAQ page.
     */
    public function faq(): View|ViewFactory
    {
        return view('faq');
    }
}
