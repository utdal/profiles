@extends('layout')
@section('title', $search ? 'Search Results' : 'All Profiles')
@section('header')
	@include('nav')
	@push('breadcrumbs')
		<li class="breadcrumb-item active" aria-current="page">
			@if(!empty($search)) Search Results @else All Profiles @endif
		</li>
	@endpush
	@include('breadcrumbs')
@stop
@section('content')

<div class="container profile-container">
	@if(!empty($search))
		<h1 class="display-4"><span class="fas fa-search"></span> Search Results</h1>
	@endif
	@if($schools->isNotEmpty())
		<h1>Schools (by Keyword: {{ $search }})</h1>

		@foreach($schools as $school)
			<h2><a href="{{ route('schools.show', $school) }}"><span class="fas fa-university"></span> {{ $school->display_name }}: View Profiles</a></h2>
		@endforeach
	@endif
		@if(isset($tag_profiles) && $tag_profiles->isNotEmpty())
		    <h1 id="tag-profiles">Profiles (by Tag: {{$search}})</h1>

		    <div class="profiles d-flex flex-wrap justify-content-around justify-content-md-between">
		        @foreach ($tag_profiles as $profile)
		            @include('profiles.panel')
		        @endforeach
		    </div>

		    <div class="paginator">
		        {!! $tag_profiles->appends(Request::except('tag'))->render() !!}
		    </div>
		@endif
		@if($profiles->isNotEmpty())
		    <h1 id="profiles">Profiles (by Name)</h1>

		    <div class="profiles d-flex flex-wrap justify-content-around justify-content-md-between">
		        @foreach ($profiles as $profile)
		            @include('profiles.panel')
		        @endforeach
		    </div>

		    <div class="paginator">
		        {{ $profiles->appends(Request::except('page'))->links() }}
		    </div>
		@endif
		@if($keyword_profiles->isNotEmpty())
		    <h1 id="keyword-profiles">Profiles (by Keyword: {{$search}})</h1>

		    <div class="profiles d-flex flex-wrap justify-content-around justify-content-md-between">
		        @foreach ($keyword_profiles as $profile)
		            @include('profiles.panel')
		        @endforeach
		    </div>

		    <div class="paginator">
		        {!! $keyword_profiles->appends(Request::except('key'))->render() !!}
		    </div>
		@endif
		@if($profiles->isEmpty() && $keyword_profiles->isEmpty() && (isset($tag_profiles) && $tag_profiles->isEmpty()) && $schools->isEmpty())
		    <h1 class="text-center" style="margin-top: 200px; margin-bottom: 300px;">No Results.</h1>
		@endif
</div>

@stop
