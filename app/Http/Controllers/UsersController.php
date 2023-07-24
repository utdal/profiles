<?php

namespace App\Http\Controllers;

use App\Helpers\Contracts\LdapHelperContract;
use App\Http\Requests\UserStoreRequest;
use App\Profile;
use App\Role;
use App\School;
use App\Student;
use App\User;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class UsersController extends Controller
{
    /**
     * Controller constructor. Middleware can be defined here.
     */
    public function __construct()
    {
        $this->middleware('auth')->only([
            'index',
            'create',
            'store',
            'edit',
            'update',
            'destroy',
            'confirmDestroy',
        ]);

        $this->middleware('can:viewAdminIndex,App\User')->only('index');

        $this->middleware('can:view,user')->only('show');

        $this->middleware('can:create,App\User')->only([
            'create',
            'store',
        ]);

        $this->middleware('can:update,user')->only([
            'edit',
            'update',
        ]);

        $this->middleware('can:delete,user')->only([
            'destroy',
            'confirmDelete',
        ]);
    }

    /**
     * Display a listing of Users.
     */
    public function index(): View|ViewContract
    {
        return view('users.index');
    }

    /**
     * Show the specified User's info.
     */
    public function show(User $user): View|ViewContract
    {
        return view('users.show', [
            'user' => $user,
            'shouldnt_sync_attributes' => $user->shouldntSync('attributes'),
            'shouldnt_sync_roles' => $user->shouldntSync('roles'),
            'shouldnt_sync_school' => $user->shouldntSync('school'),
        ]);
    }

    /**
     * Show the specified User's bookmarks.
     */
    public function showBookmarks(User $user): View|ViewContract
    {
        return view('users.bookmarks', [
            'user' => $user,
            'profile_bookmarks' => $user->bookmarked(Profile::class)->orderBy('last_name')->get(),
            'student_bookmarks' => $user->bookmarked(Student::class)->orderBy('full_name')->get(),
        ]);
    }

    /**
     * Show the view to add a new user
     */
    public function create(): View|ViewContract
    {
        return view('users.create');
    }

    /**
     * Store a new user in the database.
     */
    public function store(UserStoreRequest $request, LdapHelperContract $ldap): RedirectResponse
    {
        $name = $request->input('name');
        $user = $ldap->getUser($name);

        if ($user) {
            if ($request->input('create_profile')) {
                return redirect()->route('profiles.create', ['user' => $user]);
            }

            return redirect()->route('users.index')->with('flash_message', "Added user {$name}");
        }

        return back()->with('flash_message', "Unable to find user with username &ldquo;{$name}&rdquo; in the directory.");
    }

    /**
     * Show the view to edit the specified User
     */
    public function edit(User $user): View|ViewContract
    {
        $roles = Role::all();
        $school_editor_role = $roles->firstWhere('name', 'school_profiles_editor');
        $department_editor_role = $roles->firstWhere('name', 'department_profiles_editor');
        $schools = School::pluck('display_name','id')->all();
        $departments = User::whereNotNull('department')
                        ->orderBy('department')
                        ->select('department')
                        ->distinct()
                        ->pluck('department', 'department')
                        ->all();

        return view('users.edit', compact('user','roles','schools','departments','school_editor_role','department_editor_role'));
    }

    /**
     * Update the User in the database.
     */
    public function update(User $user, Request $request): RedirectResponse
    {
        $user->update($request->all());

        if ($request->no_sync || $request->additional_departments || $request->additional_schools || $user->setting()->exists()) {
            $user->setting()->updateOrCreate(['user_id' => $user->id,], [
                'additional_departments' => $request->additional_departments ? explode(',', $request->additional_departments) : null,
                'additional_schools' => $request->additional_schools ?? null,
                'no_sync' => $request->no_sync ?? null,
            ]);
        }

        $user->roles()->sync($request->input('role_list') ?: []);

        Cache::tags(['profiles'])->flush();

        return redirect()->route('users.show', [$user->pea])
            ->with('flash_message', 'The user has been updated.');
    }

    /**
     * Confirm deletion of the specified user
     */
    public function confirmDelete(User $user): View|ViewContract|RedirectResponse
    {
        /** @var User */
        $logged_in_user = Auth::user();

        if ($logged_in_user->is($user)) {
            return back()->with([
                'flash_message' => 'Sorry, you cannot remove yourself. To remove this user, first log in as a different site admin.',
                'flash_message_type' => 'danger',
            ]);
        }

        return view('users.delete', compact('user'));
    }

    /**
     * Remove the specified user from the database
     */
    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return redirect()->route('users.index')->with('flash_message', 'The user has been removed.');
    }

}
