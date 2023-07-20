<?php

namespace App\Http\Controllers;

use App\Events\StudentViewed;
use App\School;
use App\Setting;
use App\Student;
use App\StudentData;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

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
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
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
     * Create a new student research profile.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(Request $request)
    {
        $student = $request->user()->studentProfiles->first() ?? $this->store($request);

        if ($student) {
            return redirect()->route('students.edit', ['student' => $student]);
        }

        return back()->with('flash_message', "Unable to create Student Research Application. Please try again later.");
    }

    /**
     * Store a newly created student research profile in storage.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response|false
     */
    public function store(Request $request)
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
     * Get the list of schools participating in student research
     *
     * @return \Illuminate\Support\Collection
     */
    protected function participatingSchools()
    {
        $names = json_decode(optional(Setting::whereName('student_participating_schools')->first())->value ?? "[]");

        return empty($names) ? collect([]) : School::withNames($names)->pluck('display_name', 'short_name');
    }

    /**
     * Get any custom questions for the student application form
     *
     * @return \Illuminate\Support\Collection<string, Collection>
     */
    protected function customQuestions(): Collection
    {
        $questions = json_decode(Setting::whereName('student_questions')->first()?->value ?? "[]", true);

        return collect($questions)->groupBy('school');
    }

    /**
     * Display the specified student research profile.
     *
     * @param  Request  $request
     * @param  Student  $student
     * @return \Illuminate\View\View
     */
    public function show(Request $request, Student $student)
    {
        if ($request->user() && $request->user()->userOrDelegatorhasRole('faculty')) {
            StudentViewed::dispatch($student);
        }

        $student->load(['research_profile', 'stats', 'faculty', 'user']);

        return view('students.show', [
            'student' => $student,
            'schools' => $this->participatingSchools(),
            'custom_questions' => $this->customQuestions(),
            'languages' => StudentData::$languages,
            'majors' => StudentData::majors(),
        ]);
    }

    /**
     * Show the form for editing the specified student research profile.
     *
     * @param  Student  $student
     * @return \Illuminate\View\View
     */
    public function edit(Student $student)
    {
        return view('students.edit', [
            'student' => $student,
            'schools' => $this->participatingSchools(),
            'custom_questions' => $this->customQuestions(),
            'languages' => StudentData::$languages,
            'majors' => StudentData::majors(),
        ]);
    }

    /**
     * Update the specified student research profile in storage.
     *
     * @param  Request  $request
     * @param  Student  $student
     * @return \Illuminate\Http\RedirectResponse
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

        $student->faculty()->sync($request->faculty ?? []);

        return redirect()->route('students.show', ['student' => $student])
            ->with('flash_message', ($updated && $research_profile_updated) ? 'Submitted!' : 'Sorry, unable to save.');
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
}
