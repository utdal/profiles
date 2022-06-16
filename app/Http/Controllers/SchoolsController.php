<?php

namespace App\Http\Controllers;

use App\Profile;
use App\School;
use Illuminate\Http\Request;

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
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $schools = School::get();

        return view('schools.index', compact('schools'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('schools.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $school = School::create($request->all());

        $message = $school ? "Created school $school->display_name" : "Unable to create school";

        return redirect()->route('schools.index')->with('flash_message', $message);
    }

    /**
     * Display the specified resource.
     *
     * @param  School  $school
     * @return \Illuminate\View\View
     */
    public function show(School $school)
    {
        $profiles = Profile::fromSchoolId($school->id)->public()->paginate(24);

        return view('schools.show', compact('school', 'profiles'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  School  $school
     * @return \Illuminate\View\View
     */
    public function edit(School $school)
    {
        return view('schools.edit', compact('school'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  School  $school
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, School $school)
    {
        $updated = $school->update($request->all());

        $message = $updated ? "Updated school $school->display_name" : "Unable to update school";

        return redirect()->route('schools.index')->with('flash_message', $message);
    }

}
