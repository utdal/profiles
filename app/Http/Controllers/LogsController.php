<?php

namespace App\Http\Controllers;

use App\LogEntry;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\View\View;

class LogsController extends Controller
{
    /**
     * Controller constructor. Middleware can be defined here.
     */
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware('can:viewAdminIndex,App\LogEntry')->only('index');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|ViewContract
    {
        return view('logs.index', [
            'logs' => LogEntry::with('user')
                ->searchFor($request->log_search)
                ->orderByDesc('id')
                ->paginate(30),
            'log_search' => $request->log_search,
        ]);
    }

}
