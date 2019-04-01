<?php

namespace App\Http\Controllers;

use App\School;
use Illuminate\Http\Request;

class SchoolsController extends Controller
{

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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $schools = School::get();

        return view('schools.index', compact('schools'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('schools.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
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
     * @param  \App\School  $school
     * @return \Illuminate\Http\Response
     */
    public function show(School $school)
    {
        $profiles = $school->profiles()->public()->paginate(24);

        return view('schools.show', compact('school', 'profiles'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\School  $school
     * @return \Illuminate\Http\Response
     */
    public function edit(School $school)
    {
        return view('schools.edit', compact('school'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\School  $school
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, School $school)
    {
        $updated = $school->update($request->all());

        $message = $updated ? "Updated school $school->display_name" : "Unable to update school";

        return redirect()->route('schools.index')->with('flash_message', $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\School  $school
     * @return \Illuminate\Http\Response
     */
    public function destroy(School $school)
    {
        //
    }
}
