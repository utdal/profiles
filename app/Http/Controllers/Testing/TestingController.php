<?php

namespace App\Http\Controllers\Testing;

use App\Role;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestingController extends Controller
{
    /**
     * Controller constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Attach the specified role to the user.
     *
     * @param Request $request
     * @param string $name Name of the Role to attach
     * @return \Illuminate\Http\RedirectResponse
     */
    public function attachRole(Request $request, $name)
    {
        $role = Role::whereName($name)->firstOrFail();
        $request->user()->attachRole($role);

        return back()->with(['flash_message' => 'You now have the role: ' . $role->display_name]);
    }

    /**
     * Detach the specified role from the user.
     *
     * @param Request $request
     * @param string $name Name of the Role to attach
     * @return \Illuminate\Http\RedirectResponse
     */
    public function detachRole(Request $request, $name)
    {
        $role = Role::whereName($name)->firstOrFail();
        $request->user()->detachRole($role);

        return back()->with(['flash_message' => 'You no longer have the role: ' . $role->display_name]);
    }

    /**
     * Show a list of possible users to login as
     *
     * @param Request $request
     * @return string
     */
    public function showLoginAsList(Request $request)
    {
        $output = "<h1>Log in as a Different User</h1>\n";
        $output .= "<ul>\n";
        foreach (User::orderBy('lastname')->get() as $user) {
            $url = route('testing.login_as.login', ['id' => $user->id]);
            $output .= "<li><a href='{$url}'>{$user->display_name}</a></li>\n";
        }
        $output .= "</ul>";

        return $output;
    }

    /**
     * Login as the specified user
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function loginAs(Request $request, $id)
    {
        auth()->loginUsingId($id);

        return redirect('/');
    }
}
