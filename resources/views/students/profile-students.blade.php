@extends('layout')
@section('title', "Student Research Applications for {$profile->full_name}")
@section('header')
	@include('nav')
@stop
@section('content')

<div class="container">
    <h1><span class="fa fa-users fa-fw"></span> Student Research Applications for {{ $profile->full_name }}</h1>

    <div class="alert alert-info">
        TODO: some blurb about what to do here.
    </div>

    <livewire:profile-students :profile="$profile">
</div>

@stop