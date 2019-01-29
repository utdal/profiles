@extends('layout')
@section('title', 'All Profiles Table')
@section('header')
	@include('nav')
@stop
@section('content')
<div class="container">
    <h1>All Profiles</h1>

    {!! Form::open(['url' => route('profiles.table'), 'method' => 'get', 'class' => 'form-inline mb-4']) !!}
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
                <th>Full Name</th>
                <th>Slug</th>
                <th>Public</th>
                <th>Created</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($profiles as $profile)
            <tr>
                <td>{{ $profile->id }}</a></td>
                <td>{{ $profile->full_name }}</td>
                <td><a href="{{ route('profiles.show', ['slug' => $profile->slug]) }}">{{ $profile->slug }}</a></td>
                <td><span class="fas {{ $profile->public ? 'fa-eye' : 'fa-eye-slash text-muted' }}"></span></td>
                <td>{{ $profile->created_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="paginator">
        {{ $profiles->appends(Input::except('page'))->links() }}
    </div>

</div>

@stop
