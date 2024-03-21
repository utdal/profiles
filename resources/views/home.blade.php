@extends('layout')
@section('title', 'Home')
@section('header')
	@include('nav')
	<h1 class="sr-only">Profiles</h1>
@stop
@section('content')

@if(File::exists(public_path('/storage/video/home.jpg')) && File::exists(public_path('/storage/video/home.mp4')))
	<div class="video-cover">
			<button class="control-bt pause" aria-label="Video Playing.">
				<span><i class="fas fa-play"></i></span>
				<span><i class="fas fa-pause"></i></span>
			</button>
		<p id="video-description" class="sr-only">Scenes of campus buildings</p>
		<p id="img-description" class="sr-only">Scenes of campus buildings</p>
		<video autoplay muted loop
			id="home-video"
			poster="{{asset('storage/video/home.jpg')}}"
		>
			<source src="{{asset('storage/video/home.mp4')}}" type="video/mp4">
		</video>
	</div>
@endif

<div id="home-top" class="full-width d-flex justify-content-around justify-content-md-around align-items-center flex-wrap">

	<div id="home-search" class="card info">
		<h3>Find an expert by <a href="/browse" onclick="javascript:$('#home-search form').show(); $('#home-search .search').focus(); return false;">name or keyword...</a></h3>
		@include('_search')
		<div id="search-terms">
				@foreach($tags as $tag)
					<a href="{{ route('profiles.index', ['search' => $tag->name]) }}"><span class="badge tags-badge">{{ $tag->name }}</span></a>
				@endforeach
					<a href="{{ route('tags.index') }}"><span class="badge tags-badge">more...</span></a>
		</div>
	</div>

</div>

<div id="stats" class="full-width" style="background-image:url('{{asset('/img/60-lines.png')}}');">
	<div class="container">
	  <div class="row">
	    <div class="col align-self-start animated bounceInLeft">
	      <span class="num"><i class="fas fa-users"></i> {{number_format($num_profiles)}}</span><div>profiles</div>
	    </div>
	    <div class="col align-self-center animated bounceInUp">
	      <span class="num"><i class="fas fa-book"></i> {{number_format($num_publications)}}</span><div>publications</div>
	    </div>
	    <div class="col align-self-end animated bounceInRight">
	      <span class="num"><i class="fas fa-database"></i> {{number_format($num_datum)}}</span> <div>records</div>
	    </div>
	  </div>
	</div>
</div>

<div id="home-bottom" class="full-width">
	<div class="container profiles d-flex flex-wrap justify-content-around justify-content-md-around animated pulse">
			@foreach ($random_profile as $profile)
					@include('profiles.panel')
			@endforeach
			<div class="card info">
					<h3>About</h3>
					@if(isset($settings['description']))
							{!! $settings['description'] !!}
					@else
					<p>Providing external visibility and access to leading research experts, this platform promotes knowledge sharing and networking among leading academic experts, industry, and government agencies.</p>
					<p>Profiles offers a compendium of information from research interests and professional preparation to publications and honors, creating a platform of collaboration and facilitating innovative approaches to solving global problems and technology transfer.</p>
					@endif
			</div>
	</div>
</div>

@stop
