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
	@push('breadcrumbs')
		@if($profile->user->school)
			<li class="breadcrumb-item">
				<a href="{{ route('schools.show', ['school' => $profile->user->school]) }}">
					{{ $profile->user->school->display_name }}
				</a>
			</li>
		@endif
		<li class="breadcrumb-item active" aria-current="page">
			{{ $profile->name }}
		</li>
	@endpush
	@include('breadcrumbs')
@stop
@section('content')
<div class="profile">
	<div class="profile-header @if($information->fancy_header) fancy_header @endif" @if($information->fancy_header) style="background-image: url({{$profile->banner_url}})" @endif>
		<div class="container">
			<div class="row d-flex align-items-center @if($information->fancy_header_right)justify-content-end @endif">
				@if(!$information->fancy_header)
					<div class="col-md-5 col-sm-6">
						<img class="profile_photo" src="{{ $profile->image_url }}" alt="{{ $profile->full_name }}">
						@if($editable)
						<a class="btn-sm btn-info btn edit_photo_button offset-10 col-2" href="#" data-target="#profile_header_editor" data-toggle="modal" role="button">
							<small><i class="fas fa-camera"></i> Edit</small>
						</a>
						@endif
						
					</div>
				@endif
				<livewire:profile-header-editor-modal :profile="$profile">
				<div class="@if($information->fancy_header)col-lg-5 @else col-md-7 col-sm-6 @endif">
					<div class="contact_info">

						<h1 class="mt-sm-0">{{ $profile->name }}
							@can('delete', $profile)<a class="btn btn-danger btn-sm" href="{{ route('profiles.confirm-delete', [ $profile ]) }}" title="Archive"><i class="fas fa-archive"></i> Archive</a>@endcan 
							@if($editable)<a class="btn btn-primary btn-sm" href="{{ route('profiles.edit', [$profile->slug, 'information']) }}" title="Edit"><i class="fas fa-edit"></i> Edit</a>@endif
							<span title="Bookmark"><livewire:bookmark-button :model="$profile"></span>
							@if(config('pdf.enabled'))
								@can('export', $profile)<a class="btn btn-primary btn-sm" href="{{ route('profiles.export.pdf', [ $profile ]) }}" title="Export as PDF"><i class="fas fa-download"></i> PDF</a>@endcan
							@endif
						</h1>
						<div class="profile-titles">
							@if($information->distinguished_title) <div class="profile-title">{{ $information->distinguished_title }}</div> @endif
							@if($information->title) <div class="profile-title">{{ $information->title }}</div> @endif
							@if($information->secondary_title) <div class="profile-title">{{ $information->secondary_title }}</div> @endif
							@if($information->tertiary_title) <div class="profile-title">{{ $information->tertiary_title }}</div> @endif
						</div>
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
								@if($information->show_accepting_students || $information->show_not_accepting_students)
								<p class="mt-3 mb-0">
									@if($information->show_accepting_students)
										<p class="m-0"><small><i class="fas fa-fw fa-user-graduate" aria-hidden="true"></i> Currently accepting {{ collect(['undergraduate' => $information->accepting_students, 'graduate' => $information->accepting_grad_students])->filter()->keys()->implode(' and ') }} students</small></p>
									@endif
									@if($information->show_not_accepting_students)
										<p class="m-0 text-muted"><small><i class="fas fa-fw fa-user-slash" aria-hidden="true"></i> Not currently accepting {{ collect(['undergraduate' => $information->not_accepting_students, 'graduate' => $information->not_accepting_grad_students])->filter()->keys()->implode(' or ') }} students</small></p>
									@endif
								</p>
								@endif
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
		@if($editable & $information->fancy_header)
			<div class="d-flex align-items-end flex-column edit_banner_button">
				<a class="btn-sm btn-info btn " href="#" data-target="#profile_header_editor" data-toggle="modal" role="button">
					<small><i class="fas fa-camera"></i> Edit</small>
				</a>
			</div>
		@endif
		
	</div>
	<nav id="links" class="container links" aria-label="profile sections">
		<ul>
			@if($profile->preparation()->exists())<li><a href="#preparation">Professional Preparation</a></li>@endif
			@if($profile->areas()->exists())<li><a href="#areas">Research Areas</a></li>@endif
			@if($profile->publications()->exists())<li><a href="#publications">Publications</a></li>@endif
			@if($profile->appointments()->exists())<li><a href="#appointments">Appointments</a></li>@endif
			@if($profile->awards()->exists())<li><a href="#awards">Awards</a></li>@endif
			@if($profile->projects()->exists())<li><a href="#projects">Projects</a></li>@endif
			@if($profile->presentations()->exists())<li><a href="#presentations">Presentations</a></li>@endif
			@if($profile->additionals()->exists())<li><a href="#additionals">Additional Information</a></li>@endif
			@if($profile->news()->public()->exists())<li><a href="#news">News</a></li>@endif
			@if($profile->activities()->exists())<li><a href="#activities">Activities</a></li>@endif
			@if($profile->affiliations()->exists())<li><a href="#affiliations">Affiliations</a></li>@endif
			@if($profile->support()->exists())<li><a href="#funding">Support</a></li>@endif
		</ul>
	</nav>
	<div class="container card-columns main_areas">
		<livewire:profile-data-card :editable="$editable" :profile="$profile" :paginated="false" data_type="preparation">
		<livewire:profile-data-card :editable="$editable" :profile="$profile" :paginated="false" data_type="areas">
		<livewire:profile-data-card :editable="$editable" :profile="$profile" :paginated="$paginated" data_type="publications">
		<livewire:profile-data-card :editable="$editable" :profile="$profile" :paginated="$paginated" data_type="awards">
		<livewire:profile-data-card :editable="$editable" :profile="$profile" :paginated="$paginated" data_type="appointments">
		<livewire:profile-data-card :editable="$editable" :profile="$profile" :paginated="$paginated" data_type="projects">
		<livewire:profile-data-card :editable="$editable" :profile="$profile" :paginated="$paginated" data_type="presentations">
		<livewire:profile-data-card :editable="$editable" :profile="$profile" :paginated="$paginated" data_type="additionals">
		<livewire:profile-data-card :editable="$editable" :profile="$profile" :paginated="$paginated" :public_filtered="true" data_type="news">
		<livewire:profile-data-card :editable="$editable" :profile="$profile" :paginated="false" data_type="activities">
		<livewire:profile-data-card :editable="$editable" :profile="$profile" :paginated="$paginated" data_type="affiliations">
		<livewire:profile-data-card :editable="$editable" :profile="$profile" :paginated="$paginated" data_type="support">
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
