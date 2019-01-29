<?php

namespace App\Http\Middleware;

use App\Profile;
use Closure;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Contracts\Auth\Factory as Auth;

class AuthorizeCreateProfile
{
    /**
     * The authentication factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * The gate instance.
     *
     * @var \Illuminate\Contracts\Auth\Access\Gate
     */
    protected $gate;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function __construct(Auth $auth, Gate $gate)
    {
        $this->auth = $auth;
        $this->gate = $gate;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next)
    {
        $this->auth->authenticate();

        if ($this->gate->allows('create', Profile::class)) {
            return $next($request);
        }

        if ($request->route()->parameter('user')->is($this->auth->user()) && $this->gate->allows('createOwn', Profile::class)) {
            return $next($request);
        }

        abort(401);
    }

}
