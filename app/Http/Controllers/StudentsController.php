<?php

namespace App\Http\Controllers;

use App\Events\StudentViewed;
use App\Student;
use App\StudentData;
use App\User;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class StudentsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except('about');
        $this->middleware('can:viewAny,'.Student::class)->only('index');
        $this->middleware('can:create,'.Student::class)->only(['create', 'store']);
        $this->middleware('can:view,student')->only('show');
        $this->middleware('can:update,student')->only(['edit', 'update', 'setStatus']);
        $this->middleware('can:delete,student')->only('destroy');
    }

    public function about()
    {
        return view('students.about');
    }

    /**
     * Display the list of student research applications.
     */
    public function index(): View|ViewContract
    {
        /** @var User */
        $user = Auth::user();
        $user->loadMissing(['profiles', 'currentDelegators.profiles']);

        return view('students.index', [
            'user' => $user,
            'user_profile' => $user->profiles->first(),
            'delegator_profiles' => $user->currentDelegators->pluck('profiles')->flatten(),
        ]);
    }

    /**
     * Create a new student research application.
     */
    public function create(Request $request): RedirectResponse
    {
        $student = $request->user()->studentProfiles->first() ?? $this->store($request);

        if ($student) {
            return redirect()->route('students.edit', ['student' => $student]);
        }

        return back()->with('flash_message', "Unable to create Student Research Application. Please try again later.");
    }

    /**
     * Store a newly created student research application in the database.
     */
    public function store(Request $request): Student|false
    {
        /** @var User */
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
     * Display the specified student research application.
     */
    public function show(Request $request, Student $student): View|ViewContract
    {
        if ($request->user() && $request->user()->userOrDelegatorhasRole('faculty')) {
            StudentViewed::dispatch($student);
        }

        $student->load(['research_profile', 'stats', 'faculty', 'user']);

        return view('students.show', [
            'student' => $student,
            'schools' => Student::participatingSchools(),
            'custom_questions' => StudentData::customQuestions(),
            'languages' => StudentData::$languages,
            'majors' => StudentData::majors(),
            'not_accepting_undergrad' => true,
        ]);
    }

    /**
     * Show the form for editing the specified student research application.
     */
    public function edit(Student $student): View|ViewContract
    {
        return view('students.edit', [
            'student' => $student,
            'schools' => Student::participatingSchools(),
            'custom_questions' => StudentData::customQuestions(),
            'languages' => StudentData::$languages,
            'majors' => StudentData::majors(),
            'not_accepting_undergrad' => true,
        ]);
    }

    /**
     * Update the specified student research application in the database.
     */
    public function update(Request $request, Student $student): RedirectResponse
    {
        $updated = $student->update([
            'full_name' => $request->full_name,
            'status' => 'submitted',
        ]);

        $research_profile_updated = $student->research_profile->update([
            'type' => 'research_profile',
            'data' => $request->research_profile,
        ]);

        $student->faculty()->sync($request->faculty ?? []);

        return redirect()->route('students.show', ['student' => $student])
            ->with('flash_message', ($updated && $research_profile_updated) ? 'Submitted!' : 'Sorry, unable to save.');
    }

    /**
     * Update the status of the specified student research application.
     */
    public function setStatus(Request $request, Student $student): RedirectResponse
    {
        if ($request->status === 'drafted') {
            $updated = $student->update(['status' => 'drafted']);
        } elseif ($request->status === 'submitted') {
            $updated = $student->update(['status' => 'submitted']);
        }

        return back()
            ->with('flash_message', ($updated ?? false) ? 'Student profile status updated' : 'Not updated');
    }
}
