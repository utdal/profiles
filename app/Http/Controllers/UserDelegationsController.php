<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

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
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\View\View
     */
    public function index()
    {
        return view('users.delegations.index');
    }

    /**
     * Display the specified user's delegations.
     *
     * @param  User  $user
     * @return \Illuminate\Contracts\View\View|\Illuminate\View\View
     */
    public function show(User $user)
    {
        return view('users.delegations.show', [
            'user' => $user,
            'delegators' => $user->delegators,
        ]);
    }
}
