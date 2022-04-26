@extends('layout')
@section('title', "Student Research Applications for {$profile->full_name}")
@section('header')
	@include('nav')
@stop
@section('content')

<div class="container">
    <h1><span class="fa fa-users fa-fw"></span> Student Research Applications for {{ $profile->full_name }}</h1>

    <h2>Information</h2>

    <div class="alert alert-info">
        <ul class="fa-ul mb-0">
            <li>
                <span class="fa-li"><i class="fas fa-info-circle"></i></span> The student research applications on this page include <em>only</em> those who have expressed interest in working with you. You can organize these applications by selecting a folder from the <strong><i class="fas fa-folder-open"></i> Move To…</strong> menu.
            </li>
            <li class="mt-2">
                <span class="fa-li"><i class="fas fa-cog"></i></span> Related tasks: 
                <livewire:accepting-students-toggle :profile="$profile">
                <a class="ml-3 font-weight-bold" href="{{ route('users.delegations.show', ['user' => $profile->user]) }}" title="View/Edit {{ $profile->full_name }} Delegations"><i class="fas fa-user-friends fa-fw"></i> Let someone else do this</a>
            </li>
        </ul>
        <p class="text-center mb-0">
        </p>
    </div>

    <h2>Applications</h2>

    <livewire:profile-students :profile="$profile">
</div>

@stop