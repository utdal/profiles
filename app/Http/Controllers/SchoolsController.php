<?php

namespace App\Http\Controllers;

use App\School;
use Illuminate\Http\Request;

class SchoolsController extends Controller
{

    public function __construct()
    {
        
        $this->middleware('can:viewAdminIndex,App\User')->only(['edit', 'update']);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        $schools = School::orderBy('short_name')->get();

        for($i = -1; $i > -5; $i--){
            $school = new School;
            $school->id = $i;
            $schools->add($school);
        }

        return view('schools.edit', compact('schools'));
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
        foreach($request->schools as $id => $school){
            if($id > 0){
                $record = School::findOrFail($id);
                
                if(empty($school['name']) && empty($school['display_name']) && empty($school['short_name']) && empty($school['aliases'])){
                    $record->delete();
                }else{
                    $record->name = $school['name'];
                    $record->display_name = $school['display_name'];
                    $record->short_name = $school['short_name'];
                    $record->aliases = $school['aliases'];
                    $record->save();
                }
            }else{
                if(!empty($school['name']) && !empty($school['short_name'])){
                    School::create($school);
                }
            }
        }

        return redirect()->route('schools.edit')->with('flash_message', 'Schools updated.');
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
