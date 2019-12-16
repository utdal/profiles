@extends('layout')
@section('title', 'All Profiles')
@section('header')
	@include('nav')
@stop
@section('content')

<div class="container profile-container">
		@if(isset($tag_profiles) && !$tag_profiles->isEmpty())
		    <h1 id="keyword-profiles">Profiles (by Tag: {{$search}})</h1>

		    <div class="profiles d-flex flex-wrap justify-content-around justify-content-md-between">
		        @foreach ($tag_profiles as $profile)
		            @include('profiles.panel')
		        @endforeach
		    </div>

		    <div class="paginator">
		        {!! $tag_profiles->appends(Request::except('tag'))->render() !!}
		    </div>
		@endif
		@if(!$profiles->isEmpty())
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
		@if(!$keyword_profiles->isEmpty())
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
		@if($profiles->isEmpty() && $keyword_profiles->isEmpty() && (isset($tag_profiles) && $tag_profiles->isEmpty()))
		    <h1 class="text-center" style="margin-top: 200px; margin-bottom: 300px;">No Results.</h1>
		@endif
</div>

@stop
