@extends('layout')
@section('title', 'Unauthorized')
@section('header')
	@include('nav')
@stop
@section('content')

<div class="jumbotron">
	<div class="text-center">
		<h2>401: Unauthorized 🚫</h2>
	</div>
</div>

@include('errors/list')

@stop