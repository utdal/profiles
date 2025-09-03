@extends('layout')
@section('title', 'Unauthorized')
@section('header')
	@include('nav')
@stop
@section('content')

<div class="container d-flex justify-content-center align-items-center min-vh-100">
	<div id="download_in_process_card" class="card bg-light mb-3" style="width: 25rem !important;">
		<div class="card-header"><h5>Download started <i class="fas fa-spinner fa-spin fa-fw"></i><h5></div>
		<div class="card-body">
			<p class="card-text small">Your download request is in process, please don't close this tab.</p>
		</div>
	</div>

	<livewire:download-ready-watcher :token="$token" :profile="$profile" :polling="true"/>
</div>

@stop