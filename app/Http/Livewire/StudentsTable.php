<?php

namespace App\Http\Livewire;

use App\Student;
use App\StudentData;
use App\Helpers\Semester;
use App\Http\Livewire\Concerns\HasFilters;
use App\Http\Livewire\Concerns\HasPagination;
use App\Http\Livewire\Concerns\HasSorting;
use App\Profile;
use Livewire\Component;
use Spatie\Tags\Tag;

class StudentsTable extends Component
{
    use HasFilters;
    use HasSorting;
    use HasPagination;

    public $search_filter = '';

    public $tag_filter = '';

    public $status_filter = '';

    public $faculty_filter = '';

    public $schools_filter = '';

    public $semester_filter = '';

    public $major_filter = '';

    public $language_filter = '';

    public $animals_filter = '';

    public $credit_filter = '';

    public $graduation_filter = '';

    public $travel_filter = '';

    public $travel_other_filter = '';

    public function mount()
    {
        $this->status_filter = 'submitted';
    }

    public function getStudentsProperty()
    {
        return Student::query()
            ->with(['research_profile', 'tags'])
            ->search($this->search_filter)
            ->withTag($this->tag_filter)
            ->withStatus($this->status_filter)
            ->withFaculty($this->faculty_filter)
            ->withSchool($this->schools_filter)
            ->graduatesOn($this->graduation_filter)
            ->withLanguage($this->language_filter)
            ->withMajor($this->major_filter)
            ->willTravel($this->travel_filter)
            ->willTravelOther($this->travel_other_filter)
            ->willWorkWithAnimals($this->animals_filter)
            ->needsResearchCredit($this->credit_filter)
            ->withSemester($this->semester_filter)
            ->orderBy($this->sort_field, $this->sort_descending ? 'desc' : 'asc')
            ->paginate($this->per_page);
    }

    public function updating($name)
    {
        $this->resetPageOnChange($name);
    }

    public function updated($name, $value)
    {
        $this->emitFilterUpdatedEvent($name, $value);
    }

    public function render()
    {
        return view('livewire.students-table', [
            'filter_names' => $this->availableFilters(),
            'tags' => Tag::getWithType(Student::class),
            'faculty' => Profile::whereHas('students')->pluck('full_name', 'id'),
            'schools' => StudentData::whereType('research_profile')->pluck('data')->pluck('schools')->flatten()->unique()->filter()->sort()->values(),
            'languages' => StudentData::$languages,
            'graduation_dates' => StudentData::uniqueValuesFor('research_profile', 'graduation_date')->sort()->values(),
            'majors' => StudentData::uniqueValuesFor('research_profile', 'major')->sort()->values(),
            'semesters' => StudentData::whereType('research_profile')->pluck('data')->pluck('semesters')->flatten()->unique()->filter()
                ->sortBy(function($semester, $key) {
                    return Semester::date($semester)->toDateString();
                })->values(),
        ]);
    }
}
