<?php

namespace App\Http\Livewire;

use App\Helpers\Semester;
use App\Http\Livewire\Concerns\HasFilters;
use App\ProfileStudent;
use App\Student;
use App\StudentData;
use Livewire\Component;
use Spatie\Browsershot\Browsershot;
use Spatie\Tags\Tag;

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

    public function downloadAsPdf($download_all = true)
    {
        if ($download_all) {
            $students = $this->students;
        }
        else{
            $students = $this->students->where('application.status', $this->filing_status);
        }

        if ($students->isEmpty()) {
            $this->emit('downloadFailed');
            $this->emit('alert', "No records available for the filters applied", 'danger');
        }
        else {
            $html = '';
            $html .= view('students.download', [
                'students' => $students,
                'schools' => Student::participatingSchools(),
                'custom_questions' => StudentData::customQuestions(),
                'languages' => StudentData::$languages,
                'majors' => StudentData::majors(),
            ])->render();

            $pdf_content = Browsershot::html($html)
                            ->waitUntilNetworkIdle()
                            ->ignoreHttpsErrors()
                            ->margins(30, 15, 30, 15);

            if (config('pdf.node')) {
                $pdf_content = $pdf_content->setNodeBinary(config('pdf.node'));
            }

            if (config('pdf.npm')) {
                $pdf_content = $pdf_content->setNpmBinary(config('pdf.npm'));
            }

            if (config('pdf.modules')) {
                $pdf_content = $pdf_content->setIncludePath(config('pdf.modules'));
            }

            if (config('pdf.chrome')) {
                $pdf_content = $pdf_content->setChromePath(config('pdf.chrome'));
            }

            if (config('pdf.chrome_arguments')) {
                $pdf_content = $pdf_content->addChromiumArguments(config('pdf.chrome_arguments'));
            }

            $this->emit('downloadStarted');

            return response()->streamDownload(function () use ($pdf_content) {
                    echo $pdf_content->pdf();
                }, 'student_applications.pdf', [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'attachment; filename="student_applications.pdf"',
                ]);
        }
        $this->emit('downloadFailed');
    }

     public function downloadAsExcel($download_all = true)
    {
        if ($download_all) {
            $students = $this->students;
        }
        else{
            $students = $this->students->where('application.status', $this->filing_status);
        }

        if ($students->isEmpty()) {
            $this->emit('alert', "No records available for the filters applied", 'danger');
        }
        else {
            $student_apps = Student::downloadStudentApps($students);
            
            $this->emit('downloadStarted');

            return $student_apps->downloadExcel('students_apps.xlsx', null, true);
        }
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
