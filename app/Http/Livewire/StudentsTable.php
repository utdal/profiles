<?php

namespace App\Http\Livewire;

use App\Student;
use App\StudentData;
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

    public $per_page = 25;

    public $sort_field = 'updated_at';

    public $sort_descending = true;

    public function sortBy($field)
    {
        $this->sort_descending = ($this->sort_field === $field) ? !$this->sort_descending : false;
        $this->sort_field = $field;
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
            ->orderBy($this->sort_field, $this->sort_descending ? 'desc' : 'asc');

        return view('livewire.students-table', [
            'students' => $students_query->paginate($this->per_page),
            'tags' => Tag::getWithType(Student::class),
            'faculty' => StudentData::whereType('research_profile')->pluck('data')->pluck('faculty')->flatten()->unique()->filter()->sort()->values(),
            'schools' => StudentData::whereType('research_profile')->pluck('data')->pluck('schools')->flatten()->unique()->filter()->sort()->values(),
        ]);
    }
}
