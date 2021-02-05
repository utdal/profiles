@extends('layout')
@section('title', 'All Schools Table')
@section('header')
	@include('nav')
@stop
@section('content')
<div class="container">
    <h1>All Schools</h1>

    <table class="table table-sm table-striped">
        <thead>
            <tr>
                <th>id</th>
                <th>Name</th>
                <th>Short Name</th>
                <th>Display Name</th>
                <th>Aliases</th>
                <th>Created</th>
                <th>Updated</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($schools as $school)
            <tr>
                <td>{{ $school->id }}</td>
                <td>{{ $school->name }}</td>
                <td>{{ $school->short_name }}</td>
                <td>{{ $school->display_name }}</td>
                <td>{{ $school->aliases }}</td>
                <td>{{ $school->created_at->toDateTimeString() }}</td>
                <td>{{ $school->updated_at->toDateTimeString() }}</td>
                <td><a href="{{ route('schools.edit', [$school]) }}">Edit</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('schools.create') }}" class="btn btn-primary" role="button"><i class="fa fas fa-plus"></i> Add New School</a>

</div>
@stop
