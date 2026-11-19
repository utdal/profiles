@extends('layout')
@section('title', ucwords($tag->name) . ' Profiles')
@section('header')
	@include('nav')
	@push('breadcrumbs')
		<li class="breadcrumb-item"><a href="{{ route('tags.index') }}">All Tags</a></li>
		<li class="breadcrumb-item active" aria-current="page">{{ ucwords($tag->name) }}</li>
	@endpush
	@include('breadcrumbs')
@stop
@section('content')
<div class="container profile-container">
	<h1><i class="fas fa-tag" aria-hidden="true"></i> {{ ucwords($tag->name) }}</h1>
	<p class="text-muted">
		@if($keyword_fallback && $profiles->total() > 0)
			No profiles are tagged with &ldquo;{{ $tag->name }}&rdquo;. Showing {{ $profiles->total() }} {{ Str::plural('profile', $profiles->total()) }} that {{ $profiles->total() === 1 ? 'mentions' : 'mention' }} it.
		@else
			{{ $profiles->total() }} {{ Str::plural('profile', $profiles->total()) }} tagged with &ldquo;{{ $tag->name }}&rdquo;
		@endif
	</p>

	@if($profiles->total() === 0)
		<h2 class="text-center my-5">No profiles have this tag yet.</h2>
	@else
		<div class="profiles d-flex flex-wrap justify-content-around justify-content-md-between">
			@foreach($profiles as $profile)
				@include('profiles.panel')
			@endforeach
		</div>

		<div class="paginator">
			{{ $profiles->links() }}
		</div>
	@endif
</div>
@stop
