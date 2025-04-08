<?php

namespace App\Http\Livewire;

use App\Helpers\Semester;
use App\Http\Livewire\Concerns\HasFilters;
use App\ProfileStudent;
use App\Student;
use App\StudentData;
use Livewire\Component;
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

    protected $listeners = [
        'profileStudentStatusUpdated' => 'refreshStudents'
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

    public function getStudentsBuilderProperty()
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
            ->with('user:id,email')
            ->orderBy('last_name');
    }

    public function getStudentsProperty()
    {
        return $this->getStudentsBuilderProperty()->get();
    }

    public function updated($name, $value)
    {
        $this->emitFilterUpdatedEvent($name, $value);
    }

    public function refreshStudents()
    {
        $this->students = $this->getStudentsProperty();
    }

    public function export()
    {
        return $this->getStudentsBuilderProperty()->toCsv();
    }

    public function render()
    {
        return view('livewire.profile-students', [
            'filter_names' => $this->availableFilters(),
            'languages' => StudentData::$languages,
            'graduation_dates' => StudentData::uniqueValuesFor('research_profile', 'graduation_date')->sort()->values(),
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
