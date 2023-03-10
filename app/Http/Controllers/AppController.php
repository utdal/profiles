<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppController extends Controller
{
    /**
     * Display the App Homepage.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\View\View view
     */
    public function index()
    {
        return view('home');
    }

    /**
     * Display the FAQ page.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\View\View view
     */
    public function faq()
    {
        return view('faq');
    }
}
