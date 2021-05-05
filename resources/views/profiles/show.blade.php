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
						<img class="profile_photo" src="{{ $profile->image_url }}">
					</div>
				@endif
				<div class="@if($information->fancy_header)col-lg-5 @else col-md-7 col-sm-6 @endif">
					<div class="contact_info">
						<h2>{{ $profile->name }} @if($editable)<a class="btn btn-primary btn-sm" href="{{ route('profiles.edit', [$profile->slug, 'information']) }}"><i class="fas fa-edit"></i> Edit</a>@endif <livewire:bookmark-button :model="$profile"></h2>
						@if($information->distinguished_title) <h6>{{ $information->distinguished_title }}</h6> @endif
						@if($information->title) <h6>{{ $information->title }}</h6> @endif
						@if($information->secondary_title) <h6>{{ $information->secondary_title }}</h6> @endif
						@if($information->tertiary_title) <h6>{{ $information->tertiary_title }}</h6> @endif
							<div>
								@if($information->email)<i class="fa fa-fw fa-envelope" aria-hidden="true"></i> <a href="#" id="{{ Utils::obfuscateEmailAddress($information->email) }}" data-evaluate="profile-eml">&nbsp;</a><br>@endif
								@if($information->phone)<i class="fa fa-fw fa-phone" aria-hidden="true"></i> {{ $information->phone }}<br />@endif
								@if($information->location)<i class="fa fa-fw fa-map-marker" aria-hidden="true"></i> {{ $information->location }}<br />@endif
								@foreach(['url' => 'url_name', 'secondary_url' => 'secondary_url_name', 'tertiary_url' => 'tertiary_url_name'] as $url_key => $url_name)
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
										@else
												<i class="fa fa-fw fa-link" aria-hidden="true"></i>
										@endif
									<a href="{{$information->$url_key}}" target="_blank">@if($information->$url_name){{$information->$url_name}}@else{{"Website"}}@endif</a><br />@endif
								@endforeach
								@if($information->orc_id)<i class="fa fa-fw fa-globe" aria-hidden="true"></i> <a href="https://orcid.org/{{$information->orc_id}}" target="_blank">ORCID</a><br />@endif
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
			@if(!$publications->isEmpty())<li><a href="#publications">Publications</a></li>@endif
			@if(!$appointments->isEmpty())<li><a href="#appointments">Appointments</a></li>@endif
			@if(!$awards->isEmpty())<li><a href="#awards">Awards</a></li>@endif
			@if(!$projects->isEmpty())<li><a href="#projects">Projects</a></li>@endif
			@if(!$presentations->isEmpty())<li><a href="#presentations">Presentations</a></li>@endif
			@if(!$additionals->isEmpty())<li><a href="#additional">Additional Information</a></li>@endif
			@if(!$news->isEmpty())<li><a href="#news">News</a></li>@endif
			@if(!$activites->isEmpty())<li><a href="#activities">Activities</a></li>@endif
			@if(!$affiliations->isEmpty())<li><a href="#affiliations">Affiliations</a></li>@endif
			@if(!$support->isEmpty())<li><a href="#funding">Support</a></li>@endif
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
		@if(!$publications->isEmpty() || $editable)
			<div class="card">
				<h3 id="publications"><i class="fa fa-book" aria-hidden="true"></i> Publications
					@if($editable)
					<a class="btn btn-primary btn-sm" href="{{ route('profiles.edit', [$profile->slug, 'publications']) }}" data-toggle="class" data-toggle-class="fa-spin" data-target="#publications .fa-sync">
						@if($profile->hasOrcidManagedPublications())
							<i class="fas fa-sync"></i> Sync
						@else
							<i class="fas fa-edit"></i> Edit
						@endif
					</a>
					@endif
				</h3>
				@foreach($publications as $pub)
					<div class="entry">
						{!! Purify::clean($pub->title) !!} {{$pub->year}} - <strong>{{$pub->type}}</strong>
						@if($pub->url)
							<a target="_blank" href="{{$pub->url}}">
								<span class="fas fa-external-link-alt" title="external link to publication"></span>
							</a>
						@endif
					</div>
				@endforeach
				{!! $publications->fragment('publications')->appends(Request::except('pub'))->render() !!}
			</div>
		@endif
		@if(!$appointments->isEmpty() || $editable)
			<div class="card">
				<h3 id="appointments"><i class="fa fa-calendar" aria-hidden="true"></i> Appointments @if($editable)<a class="btn btn-primary btn-sm" href="{{ route('profiles.edit', [$profile->slug, 'appointments']) }}"><i class="fas fa-edit"></i> Edit</a>@endif</h3>
				@foreach($appointments as $appt)
					<div class="entry">
						<strong>{{$appt->appointment}}</strong>
						<br>
						<em>{{$appt->organization}}</em> [{{$appt->start_date}}@if($appt->end_date)&ndash;{{$appt->end_date}}@else<span>&ndash;Present</span>@endif]<br />
						@if($appt->description)
							{!! Purify::clean($appt->description) !!}
						@endif
					</div>
				@endforeach
				{!! $appointments->fragment('appointments')->appends(Request::except('appt'))->render() !!}
			</div>
		@endif
		@if(!$awards->isEmpty() || $editable)
			<div class="card">
				<h3 id="awards"><i class="fa fa-trophy" aria-hidden="true"></i> Awards @if($editable)<a class="btn btn-primary btn-sm" href="{{ route('profiles.edit', [$profile->slug, 'awards']) }}"><i class="fas fa-edit"></i> Edit</a>@endif</h3>
				@foreach($awards as $award)
					<div class="entry">
						<strong>{{$award->name}}</strong> - <em>{{$award->organization}}</em> @if($award->year)[{{$award->year}}]@endif<br />
					</div>
				@endforeach
				{!! $awards->fragment('awards')->appends(Request::except('awd'))->render() !!}
			</div>
		@endif
		@if(!$projects->isEmpty() || $editable)
			<div class="card">
				<h3 id="projects"><i class="fas fa-tasks" aria-hidden="true"></i> Projects @if($editable)<a class="btn btn-primary btn-sm" href="{{ route('profiles.edit', [$profile->slug, 'projects']) }}"><i class="fas fa-edit"></i> Edit</a>@endif</h3>
				@foreach($projects as $project)
					<div class="entry">
						@if($project->url)
							<h5><a href="{{$project->url}}">{{$project->title}} <i class="fas fa-link" aria-hidden="true"></i></a></h5>
						@else
							<h5>{{$project->title}}</h5>
						@endif
						@if($project->start_date)<strong>{{$project->start_date}}@if($project->end_date)&ndash;{{$project->end_date}}@endif</strong>@endif
						@if($project->description)
							<em>{!! Purify::clean($project->description) !!}</em>
						@endif
					</div>
				@endforeach
				{!! $projects->fragment('projects')->appends(Request::except('proj'))->render() !!}
			</div>
		@endif
		@if(!$presentations->isEmpty() || $editable)
			<div class="card">
				<h3 id="presentations"><i class="fas fa-laptop" aria-hidden="true"></i> Presentations @if($editable)<a class="btn btn-primary btn-sm" href="{{ route('profiles.edit', [$profile->slug, 'presentations']) }}"><i class="fas fa-edit"></i> Edit</a>@endif</h3>
				@foreach($presentations as $presentation)
					<div class="entry">
						@if($presentation->url)
							<h5><a href="{{$presentation->url}}">{{$presentation->title}} <i class="fas fa-link" aria-hidden="true"></i></a></h5>
						@else
							<h5>{{$presentation->title}}</h5>
						@endif
						@if($presentation->start_date)<strong>{{$presentation->start_date}}@if($presentation->end_date)&ndash;{{$presentation->end_date}}@endif</strong>@endif
						@if($presentation->description)
							<em>{!! Purify::clean($presentation->description) !!}</em>
						@endif
					</div>
				@endforeach
				{!! $presentations->fragment('presentations')->appends(Request::except('pres'))->render() !!}
			</div>
		@endif
		@if(!$additionals->isEmpty() || $editable)
			<div class="card">
				<h3 id="additional"><i class="fas fa-sticky-note" aria-hidden="true"></i> Additional Information @if($editable)<a class="btn btn-primary btn-sm" href="{{ route('profiles.edit', [$profile->slug, 'additionals']) }}"><i class="fas fa-edit"></i> Edit</a>@endif</h3>
				@foreach($additionals as $additional)
					<div class="entry">
						<h5><i class="far fa-sticky-note" aria-hidden="true"></i> {{$additional->title}}</h5>
							{!! Purify::clean($additional->description) !!}
					</div>
				@endforeach
				{!! $additionals->fragment('additional')->appends(Request::except('addl'))->render() !!}
			</div>
		@endif
		@if(!$news->isEmpty() || $editable)
			<div class="card">
				<h3 id="news"><i class="fas fa-newspaper" aria-hidden="true"></i> News Articles @if($editable)<a class="btn btn-primary btn-sm" href="{{ route('profiles.edit', [$profile->slug, 'news']) }}"><i class="fas fa-edit"></i> Edit</a>@endif</h3>
				@foreach($news as $article)
					<div class="entry">
						@if($article->url)
							<h5>
								<a href="{{$article->url}}" target="_blank" title="link to article">
									{{$article->title}} <i class="fas fa-external-link-alt" aria-hidden="true"></i>
								</a>
							</h5>
						@else
							<h5>{{$article->title}}</h5>
						@endif
						@if($article->image)<img src="{{ $article->imageUrl }}" class="news_image"/>@endif
						{!! Purify::clean($article->description) !!}
					</div>
				@endforeach
				{!! $news->fragment('news')->appends(Request::except('news'))->render() !!}
			</div>
		@endif
		@if(!$activites->isEmpty() || $editable)
			<div class="card">
				<h3 id="activities"><i class="fas fa-chart-line" aria-hidden="true"></i> Activities @if($editable)<a class="btn btn-primary btn-sm" href="{{ route('profiles.edit', [$profile->slug, 'activities']) }}"><i class="fas fa-edit"></i> Edit</a>@endif</h3>
				@foreach($activites as $activity)
					<div class="entry">
						<h5>{{$activity->title}}</h5>
						{!! Purify::clean($activity->description) !!}
						@if($activity->start_date)[{{$activity->start_date}}&ndash;{{$activity->end_date}}] @endif
					</div>
				@endforeach
			</div>
		@endif
		@if(!$affiliations->isEmpty() || $editable)
			<div class="card">
				<h3 id="affiliations"><i class="fas fa-users" aria-hidden="true"></i> Affiliations @if($editable)<a class="btn btn-primary btn-sm" href="{{ route('profiles.edit', [$profile->slug, 'affiliations']) }}"><i class="fas fa-edit"></i> Edit</a>@endif</h3>
				@foreach($affiliations as $affiliation)
					<div class="entry">
						<h5>{{$affiliation->title}}</h5>
						@if($affiliation->start_date)<strong>{{$affiliation->start_date}}@if($affiliation->end_date)&ndash;{{$affiliation->end_date}}@endif</strong><br>@endif
						{!! Purify::clean($affiliation->description) !!}
					</div>
				@endforeach
				{!! $affiliations->fragment('affiliations')->appends(Request::except('affl'))->render() !!}
			</div>
		@endif
		@if(!$support->isEmpty() || $editable)
			<div class="card">
				<h3 id="funding"><i class="fas fa-dollar-sign" aria-hidden="true"></i> Funding @if($editable)<a class="btn btn-primary btn-sm" href="{{ route('profiles.edit', [$profile->slug, 'support']) }}"><i class="fas fa-edit"></i> Edit</a>@endif</h3>
				@foreach($support as $funding)
					<div class="entry">
						@if($funding->url)
							<h5><a href="{{$funding->url}}">{{$funding->title}} <i class="fas fa-link" aria-hidden="true"></i></a></h5>
						@else
							<h5>{{$funding->title}}</h5>
						@endif
						<h6>{{$funding->amount}} - {{$funding->sponsor}} [{{$funding->start_date}}@if($funding->end_date)&ndash;{{$funding->end_date}}@endif]</h6>
						{{ $funding->description }}
					</div>
				@endforeach
				{!! $support->fragment('funding')->appends(Request::except('sppt'))->render() !!}
			</div>
		@endif
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
