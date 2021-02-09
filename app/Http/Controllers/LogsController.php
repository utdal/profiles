<?php

namespace App\Http\Controllers;

use App\User;
use App\LogEntry;
use Illuminate\Http\Request;

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
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->input('log_search');

        $logs = LogEntry::with('user')->where(function($query) use ($search){
            if($search){
                $query->whereIn('user_id', User::select('id')->where('display_name', 'LIKE', "%$search%"));
            }
        })
        ->orWhere('event', 'LIKE', "%$search%")
        ->orWhere('auditable_type', 'LIKE', "%$search%")
        ->orWhere('old_values', 'LIKE', "%$search%")
        ->orWhere('new_values', 'LIKE', "%$search%")
        ->orWhere('url', 'LIKE', "%$search%")
        ->orderBy('created_at', 'desc')
        ->paginate(30);

        return view('logs.index', [
            'logs' => $logs,
            'log_search' => $search,
        ]);
    }

}
