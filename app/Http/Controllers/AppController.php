<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

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

    /**
     * Display the maintenance notice shown while the site is in read-only mode.
     */
    public function maintenance(): View|ViewFactory|RedirectResponse
    {
        if (!config('app.read_only')) {
            return redirect()->route('profiles.home');
        }

        return view('maintenance');
    }
}
