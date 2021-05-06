@extends('layout')
@section('title', 'Edit Student Research Profile')
@section('header')
	@include('nav')
@stop
@section('content')

<div class="container">

    <h1 class="mb-0">Student Research Profile</h1>
    <h2 class="mt-0 text-muted">for {{ $student->full_name }}</h2>

    <div class="alert alert-primary" role="alert">
        <p>Complete and submit your student research profile below. This profile will not be public, but will be made available to faculty researchers who may be looking for students. After submitting, you can always come back later to edit or withdraw your student research profile.</p>

        <p class="mb-0">
            <strong><i class="fas fa-exclamation-circle"></i> Important: </strong> This must be submitted prior to the semester(s) for which you are applying:
            <ul class="mb-0">
                @foreach(App\Helpers\Semester::seasons() as $season)
                <li>For {{ $season }}, complete this before {{ App\Helpers\Semester::startOfSeason($season) }}</li>
                @endforeach
            </ul>
            If you edit and re-submit your profile after those dates, it will only allow you to express interest for a future semester, and your current semester interest will be removed.</p>
    </div>

    {!! Form::model($student, ['route' => ['students.update', $student]]) !!}
        @include('students.form', ['editable' => true])
    {!! Form::close() !!}

</div>

@stop