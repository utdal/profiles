<?php

namespace App\Http\Controllers;

use App\Profile;
use App\School;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SchoolsController extends Controller
{
    /**
     * Controller constructor. Middleware can be defined here.
     */
    public function __construct()
    {
        $this->middleware('auth')->except('show');

        $this->middleware('can:viewAdminIndex,App\School')->only('index');

        $this->middleware('can:create,App\School')->only([
            'create',
            'store',
        ]);

        $this->middleware('can:update,school')->only([
            'edit',
            'update',
        ]);

    }

    /**
     * Display a list of schools.
     */
    public function index(): View|ViewContract
    {
        $schools = School::get();

        return view('schools.index', compact('schools'));
    }

    /**
     * Show the form for creating a new school.
     */
    public function create(): View|ViewContract
    {
        return view('schools.create');
    }

    /**
     * Store a newly created school in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $school = School::create($request->all());

        $message = $school ? "Created school $school->display_name" : "Unable to create school";

        return redirect()->route('schools.index')->with('flash_message', $message);
    }

    /**
     * Display all profiles from the specified school.
     */
    public function show(School $school): View|ViewContract
    {
        $profiles = Profile::fromSchoolId($school->id)->public()->excludingUnlisted()->paginate(24);

        return view('schools.show', compact('school', 'profiles'));
    }

    /**
     * Show the form for editing the specified school.
     */
    public function edit(School $school): View|ViewContract
    {
        return view('schools.edit', compact('school'));
    }

    /**
     * Update the specified school in storage.
     */
    public function update(Request $request, School $school): RedirectResponse
    {
        $updated = $school->update($request->all());

        $message = $updated ? "Updated school $school->display_name" : "Unable to update school";

        return redirect()->route('schools.index')->with('flash_message', $message);
    }

}
