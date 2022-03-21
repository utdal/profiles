<?php

namespace App\Http\Livewire;

use App\Student;
use App\StudentData;
use App\Helpers\Semester;
use App\Profile;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Tags\Tag;

class StudentsTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';

    public $tag_filter = '';

    public $status_filter = 'submitted';

    public $faculty_filter = '';

    public $schools_filter = '';

    public $semester_filter = '';

    public $per_page = 25;

    public $sort_field = 'id';

    public $sort_descending = true;

    public function sortBy($field)
    {
        $this->sort_descending = ($this->sort_field === $field) ? !$this->sort_descending : false;
        $this->sort_field = $field;
    }

    public function updating($name)
    {
        // reset pagination when searching or filtering
        if (in_array($name, ['search', 'tag_filter', 'status_filter', 'faculty_filter', 'schools_filter', 'semester_filter', 'per_page'])) {
            $this->resetPage();
        }
    }

    public function render()
    {
        $students_query = Student::query()
            ->with(['research_profile', 'tags'])
            ->search($this->search)
            ->withTag($this->tag_filter)
            ->withStatus($this->status_filter)
            ->withFaculty($this->faculty_filter)
            ->withSchool($this->schools_filter)
            ->withSemester($this->semester_filter)
            ->orderBy($this->sort_field, $this->sort_descending ? 'desc' : 'asc');

        return view('livewire.students-table', [
            'students' => $students_query->paginate($this->per_page),
            'tags' => Tag::getWithType(Student::class),
            'faculty' => Profile::whereHas('students')->pluck('full_name', 'id'),
            'schools' => StudentData::whereType('research_profile')->pluck('data')->pluck('schools')->flatten()->unique()->filter()->sort()->values(),
            'semesters' => StudentData::whereType('research_profile')->pluck('data')->pluck('semesters')->flatten()->unique()->filter()
                ->sortBy(function($semester, $key) {
                    return Semester::date($semester)->toDateString();
                })->values(),
        ]);
    }
}
