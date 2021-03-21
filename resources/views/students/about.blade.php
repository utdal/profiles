@extends('layout')
@section('title', 'About Undergraduate Student Research')
@section('header')
	@include('nav')
@stop
@section('content')

<div class="container">
    <h1>Get Started with Student Research</h1>
    
    <h2><i class="fas fa-info-circle"></i> Learn</h2>
    <p>@@todo Some info or maybe a setting to display some custom wording here.</p>
    
    <h2><i class="fas fa-search"></i> Explore</h2>
    <p>@@todo Link to explore profiles, or perhaps a fancy advanced search thingy.</p>
    <p>
        <a class="btn btn-primary" href="{{ route('tags.index') }}" role="button">Explore research topics <i class="fas fa-arrow-right"></i></a>
        <a class="btn btn-primary" href="{{ route('profiles.index') }}" role="button">Explore research profiles <i class="fas fa-arrow-right"></i></a>
    </p>

    <h2><i class="fas fa-hands-helping"></i> Apply</h2>
    <p><a class="btn btn-primary" href="{{ route('students.create') }}" role="button">Apply to join a research lab <i class="fas fa-arrow-right"></i></a></p>
</div>

@stop