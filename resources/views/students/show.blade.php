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
    <h2 class="mt-0 text-muted">
        for {{ $student->full_name }}
        <a class="btn btn-primary btn-sm" href="{{ route('students.edit', [$student]) }}"><i class="fas fa-edit"></i> Edit</a>
        @if($student->status === 'drafted')
            <a class="btn btn-secondary btn-sm" href="{{ route('students.status', ['student' => $student, 'status' => 'submitted']) }}" data-toggle="tooltip" data-placement="auto" title="Submit this student profile for consideration"><i class="fas fa-check"></i> Submit</a>
        @else
            <a class="btn btn-secondary btn-sm" href="{{ route('students.status', ['student' => $student, 'status' => 'drafted']) }}" data-toggle="tooltip" data-placement="auto" title="Un-submit if you've already joined a research group or want to remove your profile from consideration"><i class="fas fa-undo"></i> Un-submit</a>
        @endif
    </h2>

    <fieldset disabled>
        @include('students.form', ['editable' => false])
    </fieldset>
</div>

@stop