@extends('layout')
@section('title', 'Maintenance')
@section('header')
	@include('nav')
@stop
@section('content')

<div class="jumbotron">
	<div class="text-center">
		<h2>Under Maintenance 🚧</h2>
		<p>
			We're currently undergoing maintenance. Search and login are temporarily disabled{{ config('app.enable_students') ? ', as are student research applications' : '' }}.
		</p>
		<p>You can still <a href="{{ route('profiles.index') }}">browse all profiles</a> or <a href="{{ route('profiles.home') }}">return to the homepage</a>.</p>
	</div>
</div>

@stop
