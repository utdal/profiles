@extends('layout')
@section('header')
	@include('nav')
	@push('breadcrumbs')
		@if($profile->user->school)
			<li class="breadcrumb-item">
				<a href="{{ route('schools.show', ['school' => $profile->user->school]) }}">
					{{ $profile->user->school->display_name }}
				</a>
			</li>
		@endif
		<li class="breadcrumb-item">
			<a href="{{ route('profiles.show', ['profile' => $profile]) }}">{{ $profile->name }}</a>
		</li>
		<li class="breadcrumb-item active" aria-current="page">
			Edit {{ ucwords($section) }}
		</li>
	@endpush
	@include('breadcrumbs')
@stop
@section('title', 'Edit ' . ucwords($section))
@section('content')
@if(View::exists('profiles.edit.' . $section))
	<div class="container">
		@include('errors.list')
		@include('profiles.edit.' . $section)
	</div>
@else
	<div class="alert alert-info" role="alert">Sorry, this section is not currently editable.</div>
	<button class="back btn btn-primary"><i class="fas fa-arrow-left"></i> Back to Profile</button>
@endif
@stop
