<?php

namespace App\Http\Controllers\Testing;

use App\Helpers\Semester;
use App\Role;
use App\User;
use App\Http\Controllers\Controller;
use App\Profile;
use Exception;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

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
     */
    public function attachRole(Request $request, string $name): RedirectResponse
    {
        $role = Role::whereName($name)->firstOrFail();
        $request->user()->attachRole($role);

        return back()->with(['flash_message' => 'You now have the role: ' . $role->display_name]);
    }

    /**
     * Detach the specified role from the user.
     */
    public function detachRole(Request $request, string $name): RedirectResponse
    {
        $role = Role::whereName($name)->firstOrFail();
        $request->user()->detachRole($role);

        return back()->with(['flash_message' => 'You no longer have the role: ' . $role->display_name]);
    }

    /**
     * Show a list of possible users to login as
     */
    public function showLoginAsList(): Response|ResponseFactory
    {
        $output = "<h1>Log in as a Different User</h1>\n";
        $output .= "<ul>\n";
        foreach (User::orderBy('lastname')->get() as $user) {
            $url = route('testing.login_as.login', ['id' => $user->id]);
            $output .= "<li><a href='{$url}'>{$user->display_name}</a></li>\n";
        }
        $output .= "</ul>";

        return response($output);
    }

    /**
     * Login as the specified user
     */
    public function loginAs(int $id): RedirectResponse
    {
        Auth::loginUsingId($id);

        return redirect()->route('profiles.home');
    }

    /**
     * Preview an email template
     */
    public function previewEmail(Request $request, string $view): View|ViewFactory
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
     */
    public function throwException(): void
    {
        throw new Exception('Just testing. This is a test exception.');
    }
}
