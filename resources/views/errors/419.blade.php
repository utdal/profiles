@extends('layout')
@section('title', 'Page Expired')
@section('header')
	@include('nav')
@stop
@section('content')

<div class="jumbotron">
	<div class="text-center">
		<h2>419: Page Expired 😭</h2>
	</div>
</div>

@include('errors/list')

@stop