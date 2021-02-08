@extends('layout')
@section('header')
	@include('nav')
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
