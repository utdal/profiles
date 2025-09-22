<?php

namespace App\Http\Livewire;

use App\Helpers\Semester;
use App\Http\Livewire\Concerns\HasFilters;
use App\Jobs\CreateStudentAppsPdf;
use App\ProfileStudent;
use App\Student;
use App\StudentData;
use Illuminate\Support\Facades\URL;
use Livewire\Component;
use Spatie\Tags\Tag;
use Illuminate\Support\Str;

class ProfileStudents extends Component
{
    use HasFilters;

    public $profile;

    public $animals_filter = '';

    public $credit_filter = '';

    public $graduation_filter = '';

    public $language_filter = '';

    public $major_filter = '';

    public $search_filter = '';

    public $schools_filter = '';

    public $semester_filter = '';

    public $travel_filter = '';

    public $travel_other_filter = '';

    public $tag_filter = '';

    public $filter_selection = [];

    public $filing_status = '';

    protected $listeners = [
        'profileStudentStatusUpdated' => 'refreshStudents',
        'getAppliedFilters' => 'updateAppliedFiltersOnDownloadMenu',
        'downloadAsPdf',
        'downloadAsExcel',
    ];

    protected $queryString = [
        'animals_filter' => ['except' => '', 'as' => 'animals'],
        'credit_filter' => ['except' => '', 'as' => 'credit'],
        'graduation_filter' => ['except' => '', 'as' => 'graduates'],
        'language_filter' => ['except' => '', 'as' => 'language'],
        'major_filter' => ['except' => '', 'as' => 'major'],
        'search_filter' => ['except' => '', 'as' => 'search'],
        'schools_filter' => ['except' => '', 'as' => 'school'],
        'travel_filter' => ['except' => '', 'as' => 'travel'],
        'travel_other_filter' => ['except' => '', 'as' => 'travel_other'],
        'tag_filter' => ['except' => '', 'as' => 'topic'],
        'semester_filter' => ['except' => '', 'as' => 'semester'],
    ];

    public function getStudentsProperty()
    {
        return $this->profile->students()
            ->submitted()
            ->search($this->search_filter)
            ->graduatesOn($this->graduation_filter)
            ->withLanguage($this->language_filter)
            ->withMajor($this->major_filter)
            ->withSemester($this->semester_filter)
            ->withTag($this->tag_filter)
            ->willTravel($this->travel_filter)
            ->willTravelOther($this->travel_other_filter)
            ->willWorkWithAnimals($this->animals_filter)
            ->needsResearchCredit($this->credit_filter)
            ->with('user:id,email', 'research_profile', 'stats', 'faculty', 'tags')
            ->orderBy('last_name')
            ->get();
    }

    public function getAllStudents()
    {
        return $this->profile->students()
            ->submitted()
            ->with('user:id,email', 'research_profile', 'stats', 'faculty', 'tags')
            ->orderBy('last_name')
            ->get();
    }

    public function updated($name, $value)
    {
        $this->emitFilterUpdatedEvent($name, $value);
        $this->updateAppliedFiltersOnDownloadMenu();
    }

    public function updateAppliedFiltersOnDownloadMenu()
    {
        $applied_filters = [
            'filters' => [],
            'filing_status' => $this->filing_status,
        ];

        foreach ($this->availableFilters() as $filter_property) {

            if (filled(trim($this->{$filter_property}))) {
                $applied_filters['filters'][$filter_property] = $this->{$filter_property};
            }
        }

        $this->emitTo('profile-students-download-menu', 'updateFilterSummary', $applied_filters);
    }

    public function refreshStudents()
    {
        $this->students = $this->getStudentsProperty();
    }

    public function downloadAsPdf($download_all = true, $filter_summary = '')
    {
        $students = $this->getStudentsForDownload($download_all);

        if (!$students) { return false; }

        $user = auth()->user();
        $token = Str::ulid();

        $download_request_url = URL::temporarySignedRoute('pdf.requestDownload', now()->addMinutes(10), ['user' => $user, 'token' => $token, 'model' => Student::class, 'ability' => 'viewAny']);

        $download_route_name = 'pdf.download';
        $filename = "Student_apps";

        CreateStudentAppsPdf::dispatch($user, $students, $filename, $download_route_name, $filter_summary, $token, Student::class, 'viewAny');
        
        $this->dispatchBrowserEvent('initiatePdfDownload', ['download_request_url' => $download_request_url]);
    }

     public function downloadAsExcel($download_all = true)
    {
        $students = $this->getStudentsForDownload($download_all);
        
        if (!$students) { return false; }

        $student_apps = Student::downloadStudentApps($students);
        
        $this->dispatchBrowserEvent('initiateXlsxDownload');

        return $student_apps->downloadExcel('Student_apps.xlsx', null, true);
    }

    public function getStudentsForDownload($download_all)
    {
        $students = $download_all ? $this->getAllStudents() : $this->students->where('application.status', $this->filing_status);

        if ($students->isEmpty()) {
            $this->dispatchBrowserEvent('noStudentRecordsFound');
            return false;
        }

        return $students;
    }

    public function render()
    {
        return view('livewire.profile-students', [
            'filter_names' => $this->availableFilters(),
            'languages' => StudentData::$languages,
            'graduation_dates' => StudentData::uniqueValuesFor('research_profile', 'graduation_date')->sort()->values()
                ->filter(function ($date) {
                    return preg_match('/^(January|February|March|April|May|June|July|August|September|October|November|December)\s\d{4}$/', $date);
                }),
            'majors' => StudentData::uniqueValuesFor('research_profile', 'major')->sort()->values(),
            'schools' => StudentData::uniqueValuesFor('research_profile', 'schools')->sort()->values(),
            'semesters' => StudentData::uniqueValuesFor('research_profile', 'semesters')
                ->sortBy(function ($semester, $key) {
                    return Semester::date($semester)->toDateString();
                })->values(),
            'tags' => Student::possibleTags(),
            'statuses' => ProfileStudent::$statuses,
            'status_icons' => ProfileStudent::$icons,
        ]);
    }
}
