@extends('layout')
@section('title', 'Student Research Profiles')
@section('header')
	@include('nav')
@stop
@section('content')

<div class="container">

    <h1>Student Research Profiles</h1>
    
    <table class="table table-sm table-striped">
        <thead>
            <tr>
                <th>id</th>
                <th>Name</th>
                <th>Interests</th>
                <th>Graduates</th>
                <th>Status</th>
                <th>Created</th>
                <th>Updated</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($students as $student)
            <tr>
                <td>{{ $student->id }}</td>
                <td><a href="{{ route('students.show', ['student' => $student]) }}">{{ $student->full_name }}</a></td>
                <td>{{ $student->tags->implode('name', ', ') }}</td>
                <td>{{ $student->research_profile->graduation_date }}</td>
                <td>{{ $student->status }}</td>
                <td>{{ $student->created_at->toFormattedDateString() }}</td>
                <td>{{ $student->updated_at->toFormattedDateString() }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@stop