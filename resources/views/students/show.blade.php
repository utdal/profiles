@extends('layout')
@section('title', "Student Research Application for {$student->full_name}")
@section('header')
    @include('nav')
    @push('breadcrumbs')
        <li class="breadcrumb-item">
            <a href="{{ route('students.about') }}">Student Research</a>
        </li>
        <li class="breadcrumb-item active">
            @can('viewAny', App\Student::class)
                <a href="{{ route('students.index') }}">All Applications</a>
            @else
                Applications
            @endcan
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            {{ $student->full_name }}
        </li>
    @endpush
    @include('breadcrumbs')
@stop

@section('content')
@include('students.student-application', [
                    'student' => $student,
                    'schools' => $schools,
                    'custom_questions' => $custom_questions,
                    'languages' => $languages,
                    'majors' => $majors,
                ])
@stop