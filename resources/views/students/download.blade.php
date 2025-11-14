@extends('layout')

@section('content')
    @foreach ($students as $student)
        @section('title', "Student Research Application for {$student->full_name}")
        @include('students.student-application', [
                        'student' => $student,
                        'schools' => $schools,
                        'custom_questions' => $custom_questions,
                        'languages' => $languages,
                        'majors' => $majors,
                        'user' => $user,
                    ])
        <div style="page-break-after: always;"></div>
    @endforeach
@stop