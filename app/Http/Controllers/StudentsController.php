<?php

namespace App\Http\Controllers;

use App\Student;
use App\StudentData;
use Illuminate\Http\Request;

class StudentsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->only([
            'index',
            'create',
            'store',
            'edit',
            'update',
            'destroy',
        ]);
    }

    public function about()
    {
        return view('students.about');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $students = Student::with(['research_profile', 'tags'])->paginate(50);

        return view('students.index', ['students' => $students]);
    }

    /**
     * Create a new student research profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $student = $request->user()->studentProfiles->first() ?? $this->store($request);

        if ($student) {
            return redirect()->route('students.edit', ['student' => $student]);
        }

        return back()->with('flash_message', "Unable to create Student Research Profile. Please try again later.");
    }

    /**
     * Store a newly created student research profile in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /** @var App\User */
        $user = $request->user();

        $student = $user->studentProfiles()->create([
            'slug' => $user->pea,
            'full_name' => $user->display_name,
            'first_name' => $user->firstname,
            'last_name' => $user->lastname,
            'type' => 'undergraduate',
            'status' => 'drafted',
        ]);

        if (!$student) {
            return false;
        }

        $student->data()->create([
            'type' => 'research_profile',
            'data' => [],
        ]);

        return $student;
    }

    /**
     * Display the specified student research profile.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        return view('students.show', ['student' => $student]);
    }

    /**
     * Show the form for editing the specified student research profile.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        return view('students.edit', ['student' => $student]);
    }

    /**
     * Update the specified student research profile in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Student $student)
    {
        $updated = $student->update([
            'full_name' => $request->full_name,
            'status' => 'submitted',
        ]);

        $research_profile_updated = $student->research_profile->update([
            'type' => 'research_profile',
            'data' => $request->research_profile,
        ]);

        return redirect()->route('students.show', ['student' => $student])
            ->with('flash_message', ($updated && $research_profile_updated) ? 'Saved!' : 'Sorry, unable to save.');
    }

    public function setStatus(Request $request, Student $student)
    {
        if ($request->status === 'drafted') {
            $updated = $student->update(['status' => 'drafted']);
        } elseif ($request->status === 'submitted') {
            $updated = $student->update(['status' => 'submitted']);
        }

        return back()
            ->with('flash_message', ($updated ?? false) ? 'Student profile status updated' : 'Not updated');
    }

    /**
     * Remove the specified student research profile from storage.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        dd($student);
    }
}
