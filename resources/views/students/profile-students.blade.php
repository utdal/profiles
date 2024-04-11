@extends('layout')
@section('title', "Student Research Applications for {$profile->full_name}")
@section('header')
	@include('nav')
    @push('breadcrumbs')
        <li class="breadcrumb-item">
            <a href="{{ route('students.about') }}">Student Research</a>
        </li>
        <li class="breadcrumb-item">
            @can('viewAny', App\Student::class)
                <a href="{{ route('students.index') }}">All Applications</a>
            @else
                Applications
            @endcan
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            Applications for {{ $profile->full_name }}
        </li>
    @endpush
    @include('breadcrumbs')
@stop
@section('content')

<div class="container">
    <h1><span class="fa fa-users fa-fw"></span> Student Research Applications for {{ $profile->full_name }}</h1>

    <h2>Information</h2>

    <div class="alert alert-info">
        <ul class="fa-ul mb-0">
            <li>
                <span class="fa-li"><i class="fas fa-info-circle"></i></span> The student research applications on this page include <strong>only</strong> those who have expressed interest in working with you. You can organize these applications by selecting a folder from the <strong><i class="fas fa-folder-open"></i> Move To…</strong> menu.
            </li>
            <li>
                <span class="fa-li"><i class="fas fa-info-circle"></i></span> To see <strong>all</strong> student applications (including those on which you were not expressly listed), go to the <a href="{{ route('students.index') }}"><i class="fas fa-users mr-1"></i>All Student Research Applications</a> page.
            </li>
            <li class="mt-3">
                <span class="fa-li"><i class="fas fa-cog"></i></span> Related tasks: 
                <span class="dropdown student-filer">
                    <button
                        class="btn btn-primary dropdown-toggle py-1"
                        type="button"
                        id="notAcceptingStudentsButton"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-controls="notAcceptingStudentsMenu"
                        aria-expanded="false"
                    >
                        <i class="fas fa-user-slash fa-fw"></i> Indicate you're not accepting students
                    </button>
                    <div
                        id="notAcceptingStudentsMenu"
                        class="dropdown-menu p-4"
                        aria-labelledby="notAcceptingStudentsButton"
                    >
                        <p>
                            <small class="form-text text-muted">Checking the box below will show a standard note on your profile that you're not currently accepting undergraduate students for research.</small>
                        </p>
                        <livewire:accepting-students-toggle :profile="$profile">
                        <p class="text-right mt-3 mb-0">
                            <small><a href="{{ route('profiles.edit', ['profile' => $profile->slug, 'section' => 'information']) }}#show_not_accepting">Additional options <i class="fas fa-caret-right"></i></a></small>
                        </p>
                    </div>
                </span>
                @can('viewDelegations', $profile->user)
                    <a class="btn btn-primary ml-3 py-1" href="{{ route('users.delegations.show', ['user' => $profile->user]) }}" title="View/Edit {{ $profile->full_name }} Delegations"><i class="fas fa-user-friends fa-fw"></i> Let someone else do this</a>
                @endcan
            </li>
        </ul>
        <p class="text-center mb-0">
        </p>
    </div>

    <h2>Applications</h2>

    <livewire:profile-students :profile="$profile">
</div>

@stop