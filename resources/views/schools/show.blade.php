@extends('layout')
@section('title', "$school->display_name")
@section('header')
	@include('nav')
    @push('breadcrumbs')
        <li class="breadcrumb-item active" aria-current="page">
            {{ $school->display_name }}
        </li>
    @endpush
    @include('breadcrumbs')
@stop
@section('content')
<div class="container">
    <h1><span class="fas fa-university"></span> {{ $school->display_name }} Profiles</h1>

    <div class="profiles d-flex flex-wrap justify-content-around justify-content-md-between">
        @forelse ($profiles as $profile)
            @include('profiles.panel')
        @empty
            <h1 class="text-center">No Results.</h1>
        @endforelse
    </div>

    <div class="paginator">
        {{ $profiles->appends(Request::except('page'))->links() }}
    </div>
</div>
@stop
