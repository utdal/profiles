@extends('layout')
@section('title', 'All Tags')
@section('header')
	@include('nav')
	@push('breadcrumbs')
		<li class="breadcrumb-item active" aria-current="page">
			All Tags
		</li>
	@endpush
	@include('breadcrumbs')
@stop
@section('content')

<div class="container">
	<h1><i class="fas fa-tags" aria-hidden="true"></i> All Tags</h1>
	@foreach($tag_groups as $letter => $tags)
	<div class="row">
		<div class="col-sm-2 col-lg-1">
			<h2 class="display-4">{{ $letter }}</h2>
		</div>
		<div class="col-sm pt-sm-5">
		@foreach($tags as $tag)
			<a href="{{ route('profiles.index', ['search' => $tag->name]) }}" class="badge badge-primary tags-badge large">
				{{ ucwords($tag->name) }}
			</a> 
		@endforeach
		</div>
	</div>
	@endforeach
</div>

@stop