<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppController extends Controller
{
    /**
     * Display the App Homepage.
     *
     * @return index view
     */
    public function index()
    {
        return view('home');
    }

    /**
     * Display the FAQ page.
     *
     * @return index view
     */
    public function faq()
    {
        return view('faq');
    }
}
