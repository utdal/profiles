<?php

namespace App\Http\Livewire;

use App\Student;
use App\StudentData;
use Livewire\Component;
use Spatie\Tags\Tag;

class StudentsTable extends Component
{
    public $search = '';

    public $tag_filter = '';

    public $status_filter = 'submitted';

    public $faculty_filter = '';

    public $schools_filter = '';

    public function render()
    {
        $students_query = Student::query()
            ->with(['research_profile', 'tags'])
            ->search($this->search)
            ->withTag($this->tag_filter)
            ->withStatus($this->status_filter)
            ->withFaculty($this->faculty_filter)
            ->withSchool($this->schools_filter);

        return view('livewire.students-table', [
            'students' => $students_query->paginate(50),
            'tags' => Tag::getWithType(Student::class),
            'faculty' => StudentData::whereType('research_profile')->pluck('data')->pluck('faculty')->flatten()->unique(),
            'schools' => StudentData::whereType('research_profile')->pluck('data')->pluck('schools')->flatten()->unique(),
        ]);
    }
}
