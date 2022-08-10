@extends('layout')
@section('title', $profile->name)
@section('head')
<meta name="twitter:card" content="summary">
<meta name="twitter:site" content="UT Dallas Profiles">
<meta name="twitter:title" content="{{ $profile->name }}">
<meta name="twitter:description" content="View the full research profile.">
<meta name="twitter:image" content="{{ $profile->image_url }}">
<meta property="og:title" content="{{ $profile->name }}" />
<meta property="og:description" content="View the full research profile." />
<meta property="og:type" content="profile" />
<meta property="og:url" content="{{ $profile->url }}" />
<meta property="og:image" content="{{ $profile->image_url }}" />
<link rel="canonical" href="{{ $profile->url }}">
@stop
@section('header')
	@include('nav')
@stop
@section('content')
<div class="profile">
	<div class="profile-header @if($information->fancy_header) fancy_header @endif" @if($information->fancy_header) style="background-image: url({{$profile->banner_url}})" @endif>
		<div class="container">
			<div class="row d-flex align-items-center @if($information->fancy_header_right)justify-content-end @endif">
				@if(!$information->fancy_header)
					<div class="col-md-5 col-sm-6">
						<img class="profile_photo" src="{{ $profile->image_url }}" alt="{{ $profile->full_name }}">
					</div>
				@endif
				<div class="@if($information->fancy_header)col-lg-5 @else col-md-7 col-sm-6 @endif">
					<div class="contact_info">

						<h2 class="mt-sm-0">{{ $profile->name }}
							@can('delete', $profile)<a class="btn btn-danger btn-sm" href="{{ route('profiles.confirm-delete', [ $profile ]) }}" title="Archive"><i class="fas fa-archive"></i> Archive</a>@endcan 
							@if($editable)<a class="btn btn-primary btn-sm" href="{{ route('profiles.edit', [$profile->slug, 'information']) }}" title="Edit"><i class="fas fa-edit"></i> Edit</a>@endif
							<span title="Bookmark"><livewire:bookmark-button :model="$profile"></span>
						</h2>
						@if($information->distinguished_title) <h6>{{ $information->distinguished_title }}</h6> @endif
						@if($information->title) <h6>{{ $information->title }}</h6> @endif
						@if($information->secondary_title) <h6>{{ $information->secondary_title }}</h6> @endif
						@if($information->tertiary_title) <h6>{{ $information->tertiary_title }}</h6> @endif
						@if($information->profile_summary) <p class="profile_summary">{{ $information->profile_summary }}</p> @endif
							<div>
								@if($information->email)<i class="fa fa-fw fa-envelope" aria-label="Email address"></i> <a href="#" id="{{ Utils::obfuscateEmailAddress($information->email) }}" data-evaluate="profile-eml">&nbsp;</a><br>@endif
								@if($information->phone)<i class="fa fa-fw fa-phone" aria-label="Phone number"></i> {{ $information->phone }}<br />@endif
								@if($information->location)<i class="fa fa-fw fa-map-marker" aria-label="Location"></i> {{ $information->location }}<br />@endif
								@foreach(['url' => 'url_name', 'secondary_url' => 'secondary_url_name', 'tertiary_url' => 'tertiary_url_name', 'quaternary_url' => 'quaternary_url_name', 'quinary_url' => 'quinary_url_name'] as $url_key => $url_name)
									@if($information->$url_key)
										@if(strpos($information->$url_key, 'twitter') !== false)
												<i class="fab fa-fw fa-twitter" aria-hidden="true"></i>
										@elseif(strpos($information->$url_key, 'facebook') !== false)
												<i class="fab fa-fw fa-facebook" aria-hidden="true"></i>
										@elseif(strpos($information->$url_key, 'instagram') !== false)
												<i class="fab fa-fw fa-instagram" aria-hidden="true"></i>
										@elseif(strpos($information->$url_key, 'github') !== false)
												<i class="fab fa-fw fa-github" aria-hidden="true"></i>
										@elseif(strpos($information->$url_key, 'linkedin') !== false)
												<i class="fab fa-fw fa-linkedin" aria-hidden="true"></i>
										@elseif(strpos($information->$url_key, 'youtube') !== false)
												<i class="fab fa-fw fa-youtube" aria-hidden="true"></i>
										@elseif(strpos($information->$url_key, 'researchgate') !== false)
												<i class="fab fa-fw fa-researchgate" aria-hidden="true"></i>
										@elseif(strpos($information->$url_key, 'google') !== false)
												<i class="fab fa-fw fa-google" aria-hidden="true"></i>
										@else
												<i class="fa fa-fw fa-link" aria-hidden="true"></i>
										@endif
									<a href="{{$information->$url_key}}" target="_blank">@if($information->$url_name){{$information->$url_name}}@else{{"Website"}}@endif</a><br />@endif
								@endforeach
								@if($information->orc_id)<i class="fab fa-fw fa-orcid" aria-hidden="true"></i> <a href="https://orcid.org/{{$information->orc_id}}" target="_blank">ORCID</a><br />@endif
								@if($information->not_accepting_students)<p class="mt-3 mb-0 text-muted"><small><i class="fas fa-fw fa-user-slash" aria-hidden="true"></i> Not currently accepting students</small></p>@endif
							</div>
						@if(!$profile->tags->isEmpty() || $editable)
						<div class="protocol-tags">
						<i class="fas fa-tags" aria-hidden="true"></i><span class="sr-only">Tags:</span> @include('tags.show', ['model' => $profile]) @if($editable)<a class="btn btn-primary btn-sm badge" href="#" data-target="#{{ Illuminate\Support\Str::slug($profile->getRouteKey()) }}_tags_editor" data-toggle="modal" role="button"><i class="fas fa-edit"></i> Edit</a>@endif
						</div>
						@endif
					</div>
				</div>
				@if($information->fancy_header)
				<div class="fancy_link_container d-none d-lg-block">
					<a class="fancy_link" href="#links"><i class="fa fa-fw fa-arrow-down" aria-hidden="true"></i> About {{ $profile->name }}</a>
				</div>
				@endif
			</div>
		</div>
	</div>
	<div id="links" class="container links">
		<ul>
			@if(!$preparations->isEmpty())<li><a href="#preparation">Professional Preparation</a></li>@endif
			@if(!$research_areas->isEmpty())<li><a href="#areas">Research Areas</a></li>@endif
			@if($publications_exists)<li><a href="#publications">Publications</a></li>@endif
			@if($appointments_exists)<li><a href="#appointments">Appointments</a></li>@endif
			@if($awards_exists)<li><a href="#awards">Awards</a></li>@endif
			@if($projects_exists)<li><a href="#projects">Projects</a></li>@endif
			@if($presentations_exists)<li><a href="#presentations">Presentations</a></li>@endif
			@if($additionals_exists)<li><a href="#additional">Additional Information</a></li>@endif
			@if($news_exists)<li><a href="#news">News</a></li>@endif
			@if(!$activities->isEmpty())<li><a href="#activities">Activities</a></li>@endif
			@if($affiliations_exists)<li><a href="#affiliations">Affiliations</a></li>@endif
			@if($support_exists)<li><a href="#funding">Support</a></li>@endif
		</ul>
	</div>
	<div class="container card-columns main_areas">
	@if(!$preparations->isEmpty() || $editable)
		<div class="card">
			<h3 id="preparation"><i class="fas fa-graduation-cap" aria-hidden="true"></i> Professional Preparation @if($editable)<a class="btn btn-primary btn-sm" href="{{ route('profiles.edit', [$profile->slug, 'preparation']) }}"><i class="fas fa-edit"></i> Edit</a>@endif</h3>
			@foreach($preparations as $prep)
				<div class="entry">
					{{$prep->degree}} @if($prep->major)- {{$prep->major}}@endif
					<br>
					<strong>{{$prep->institution}}</strong>@if($prep->year) - {{$prep->year}}@endif
				</div>
			@endforeach
		</div>
	@endif
	@if(!$research_areas->isEmpty() || $editable)
			<div class="card">
				<h3 id="areas"><i class="fas fa-flask" aria-hidden="true"></i> Research Areas @if($editable)<a class="btn btn-primary btn-sm" href="{{ route('profiles.edit', [$profile->slug, 'areas']) }}"><i class="fas fa-edit"></i> Edit</a>@endif</h3>
				@foreach($research_areas as $area)
					@if($area->url)
						<h5><a href="{{$area->url}}">{{$area->title}} <i class="fas fa-link" aria-hidden="true"></i></a></h5>
					@else
						<h5>{{$area->title}}</h5>
					@endif
					{!! Purify::clean($area->description) !!}
				@endforeach
			</div>
	@endif
		<livewire:paginated-data :editable="$editable" :profile="$profile" data_type="publications">
		<livewire:paginated-data :editable="$editable" :profile="$profile" data_type="awards">
		<livewire:paginated-data :editable="$editable" :profile="$profile" data_type="appointments">
		<livewire:paginated-data :editable="$editable" :profile="$profile" data_type="projects">
		<livewire:paginated-data :editable="$editable" :profile="$profile" data_type="presentations">
		<livewire:paginated-data :editable="$editable" :profile="$profile" data_type="additionals">
		<livewire:paginated-data :editable="$editable" :profile="$profile" data_type="news">
		@if(!$activities->isEmpty() || $editable)
			<div class="card">
				<h3 id="activities"><i class="fas fa-chart-line" aria-hidden="true"></i> Activities @if($editable)<a class="btn btn-primary btn-sm" href="{{ route('profiles.edit', [$profile->slug, 'activities']) }}"><i class="fas fa-edit"></i> Edit</a>@endif</h3>
				@foreach($activities as $activity)
					<div class="entry">
						<h5>{{$activity->title}}</h5>
						{!! Purify::clean($activity->description) !!}
						@if($activity->start_date)[{{$activity->start_date}}&ndash;{{$activity->end_date}}] @endif
					</div>
				@endforeach
			</div>
		@endif
		<livewire:paginated-data :editable="$editable" :profile="$profile" data_type="affiliations">
		<livewire:paginated-data :editable="$editable" :profile="$profile" data_type="support">
	</div>
</div>
@stop
@section('scripts')
<script>
$(".show-more a").on("click", function() {
    var $this = $(this);
    var $content = $this.parent().prev("div.content");
    var linkText = $this.text().toUpperCase();

    if(linkText === "SHOW MORE"){
        linkText = "Show less";
        // $content.switchClass("hideContent", "showContent", 400);
				$content.toggleClass('showContent');
    } else {
        linkText = "Show more";
				$content.toggleClass('showContent');
        // $content.switchClass("showContent", "hideContent", 400);
    };

    $this.text(linkText);
		return false;
});
</script>
@stop
