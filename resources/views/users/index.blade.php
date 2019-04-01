@extends('layout')
@section('title', 'All Users Table')
@section('header')
	@include('nav')
@stop
@section('content')
<div class="container">
    <h1>All Users</h1>
    
    {!! Form::open(['url' => route('users.index'), 'method' => 'get', 'class' => 'form-inline mb-4']) !!}
    <div class="search input-group input-group-lg">
        <input class="search form-control" type="search" name="search" placeholder="search..." aria-label="Search" value="{{$search}}">
        <div class="input-group-append">
        <button class="btn btn-success" type="submit" data-toggle="replace-icon" data-newicon="sync" data-newiconclasses="fa-spin" data-inputrequired="nav input[type=search]">
            <i class="fas fa-search"></i><span class="sr-only">search</span>
        </button>
        </div>
    </div>
    {!! Form::close() !!}

    <table class="table table-sm table-striped">
        <thead>
            <tr>
                <th>id</th>
                <th>{{ $settings['account_name'] ?? 'Username' }}</th>
                <th>URL Name</th>
                <th>First</th>
                <th>Last</th>
                <th>Title</th>
                <th>School</th>
                <th>Dept</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr>
                <td><a href="{{ route('users.show', [$user]) }}">{{ $user->id }}</a></td>
                <td>{{ $user->name }}</td>
                <td><a href="{{ route('profiles.show', [$user->pea]) }}">{{ $user->pea }}</a></td>
                <td>{{ $user->firstname }}</td>
                <td>{{ $user->lastname }}</td>
                <td>{{ $user->title }}</td>
                <td>{{ $user->school ? $user->school->short_name : 'none' }}</td>
                <td>{{ $user->department }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="paginator">
        {{ $users->appends(Input::except('page'))->links() }}
    </div>
</div>
@stop
