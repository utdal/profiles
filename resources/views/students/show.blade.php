@extends('layout')
@section('title', 'Student Research Profile')
@section('header')
	@include('nav')
@stop
@section('content')

<div class="container">
    
    <h1 class="mb-0">
        Student Research Profile @if($student->status === 'drafted')<span class="badge rounded-pill badge-secondary">drafted</span>@endif
    </h1>
    <h2 class="my-0 text-muted">
        for {{ $student->full_name }}
        @can('update', $student)
            <a class="btn btn-primary btn-sm" href="{{ route('students.edit', [$student]) }}"><i class="fas fa-edit"></i> Edit</a>
            @if($student->status === 'drafted')
                <a class="btn btn-secondary btn-sm" href="{{ route('students.status', ['student' => $student, 'status' => 'submitted']) }}" data-toggle="tooltip" data-placement="auto" title="Submit this student profile for consideration"><i class="fas fa-check"></i> Submit</a>
            @else
                <a class="btn btn-secondary btn-sm" href="{{ route('students.status', ['student' => $student, 'status' => 'drafted']) }}" data-toggle="tooltip" data-placement="auto" title="Un-submit if you've already joined a research group or want to remove your profile from consideration"><i class="fas fa-undo"></i> Un-submit</a>
            @endif
        @endcan
        @can('viewFeedback', $student)
            <a class="btn btn-primary btn-sm" href="#student_feedback"><i class="fas fa-comment"></i> Feedback</a>
        @endcan
    </h2>
    <div class="text-muted text-right">
        <small>last updated: {{ $student->updated_at->toFormattedDateString() }}</small>
    </div>
    <hr>

    <fieldset disabled>
        @include('students.form', ['editable' => false])
    </fieldset>

    @can('viewFeedback', $student)
        <hr>
        <h2 id="student_feedback"><i class="fas fa-comment"></i> Feedback</h2>
        <livewire:student-feedback :student="$student">
    @endcan
</div>

@stop