<?php

namespace App\Http\Livewire;

use App\Helpers\Semester;
use App\ProfileStudent;
use App\Student;
use App\StudentData;
use Livewire\Component;
use Spatie\Tags\Tag;

class ProfileStudents extends Component
{
    public $profile;

    public $students = [];

    public $filtered_by = [];

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
        'profileStudentStatusUpdated' => 'refreshLists'
    ];

    public function mount()
    {
        $this->refreshLists();
    }

    public function updated($name, $value)
    {
        if ($this->isAFilter($name)) {
            if ($value === '') {
                unset($this->filtered_by[$name]);
                $this->emit('alert', "Cleared filter.", 'success');
            } else {
                $this->filtered_by[$name] = $value;
                $this->emit('alert', "Applied filter.", 'success');
            }
            $this->refreshLists();
        }
    }

    public function refreshLists()
    {
        $this->students = $this->profile->students()
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
            ->get();
    }

    public function resetFilters()
    {
        foreach (get_class_vars(self::class) as $name => $value) {
            if ($this->isAFilter($name)) {
                $this->$name = '';
            }
        }

        $this->filtered_by = [];
        $this->refreshLists();
        $this->emit('alert', "Cleared all filters.", 'success');
    }

    public function resetFilter($filter_name)
    {
        $this->$filter_name = '';
        $this->updated($filter_name, '');
    }

    protected function isAFilter($name)
    {
        return strpos($name, '_filter') !== false;
    }

    public function render()
    {
        return view('livewire.profile-students', [
            'languages' => StudentData::$languages,
            'graduation_dates' => StudentData::uniqueValuesFor('research_profile', 'graduation_date')->sort()->values(),
            'majors' => StudentData::uniqueValuesFor('research_profile', 'major')->sort()->values(),
            'schools' => StudentData::uniqueValuesFor('research_profile', 'schools')->sort()->values(),
            'semesters' => StudentData::uniqueValuesFor('research_profile', 'semesters')
                ->sortBy(function ($semester, $key) {
                    return Semester::date($semester)->toDateString();
                })->values(),
            'tags' => Tag::getWithType(Student::class),
            'statuses' => ProfileStudent::$statuses,
            'status_icons' => ProfileStudent::$icons,
        ]);
    }
}
