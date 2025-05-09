<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserDelegationsController extends Controller
{
    /**
     * Controller constructor. Middleware can be defined here.
     */
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware('can:viewAdminIndex,App\UserDelegation')->only('index');

        $this->middleware('can:viewDelegations,user')->only('show');
    }

    /**
     * Display a listing of all delegations.
     */
    public function index(): View|ViewContract
    {
        return view('users.delegations.index');
    }

    /**
     * Display the specified user's delegations.
     */
    public function show(User $user): View|ViewContract
    {
        return view('users.delegations.show', [
            'user' => $user,
            'delegators' => $user->delegators,
        ]);
    }
}
