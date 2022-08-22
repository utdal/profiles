@extends('layout')
@section('title', 'All Student Research Applications')
@section('header')
	@include('nav')
    @push('breadcrumbs')
        <li class="breadcrumb-item">
            <a href="{{ route('students.about') }}">Student Research</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            All Applications
        </li>
    @endpush
    @include('breadcrumbs')
@stop
@section('content')

<div class="container">

    <h1>All Student Research Applications</h1>

    <div class="alert alert-info mb-5">
        <ul class="fa-ul mb-0">
            <li>
                <span class="fa-li"><i class="fas fa-info-circle"></i></span> This page includes <strong>all</strong> student research applications created on this site.
            </li>
            @if($user_profile || $delegator_profiles->isNotEmpty())
                <li class="mt-3">
                    <span class="fa-li"><i class="fas fa-cog"></i></span> Related: 
                    @if($user_profile)
                        <a href="{{ route('profiles.students', ['profile' => $user_profile]) }}" class="btn btn-primary py-1">Applications for {{ $user_profile->full_name }}</a>
                    @endif
                    @foreach($delegator_profiles as $delegator_profile)
                        <a href="{{ route('profiles.students', ['profile' => $delegator_profile]) }}" class="btn btn-primary py-1 ml-3">Applications for {{ $delegator_profile->full_name }}</a>
                    @endforeach
                </li>
            @endif
        </ul>
    </div>

    <livewire:students-table>

</div>

@stop
