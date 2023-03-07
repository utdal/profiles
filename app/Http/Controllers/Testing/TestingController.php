<?php

namespace App\Http\Controllers\Testing;

use App\Helpers\Semester;
use App\Role;
use App\User;
use App\Http\Controllers\Controller;
use App\Profile;
use Exception;
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
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function loginAs(Request $request, $id)
    {
        auth()->loginUsingId($id);

        return redirect('/');
    }

    /**
     * Preview an email template
     *
     * @param Request $request
     * @param string $view The Blade email view
     * @return \Illuminate\View\View
     */
    public function previewEmail(Request $request, $view)
    {
        $default_params = [];

        if ($view === 'reviewstudents') {
            $semester = $request->semester ?: Semester::current();
            $faculty = $request->has('faculty') ? Profile::firstWhere('slug', '=', $request->faculty) : Profile::factory()->make();
            $count = $request->count ?? ($request->has('faculty') ? Profile::EagerStudentsPendingReviewWithSemester($semester)->find($faculty->id)->students->count() : '10');
            $default_params = [
                'count' => $count,
                'faculty' => $faculty,
                'name' => $faculty->full_name,
                'semester' => Semester::current(),
                'delegate' => false,
            ];
        }

        return view("emails.$view", array_merge($default_params, $request->all()));
    }

    /**
     * Throw a test exception
     *
     * @return void
     */
    public function throwException()
    {
        throw new Exception('Just testing. This is a test exception.');
    }
}
