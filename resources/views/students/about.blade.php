@extends('layout')
@section('title', 'About Undergraduate Student Research')
@section('head')
<style>
    .student-info-card a {
        color: white;
        text-decoration: underline;
    }
</style>
@stop
@section('header')
	@include('nav')
@stop
@section('content')

<div class="container">
    <h1><i class="fas fa-rocket"></i> Get Started with Student Research</h1>

    <div class="card student-info-card border-0 bg-dark text-white">
        @isset($settings['student_info_image'])
        <img class="card-img" src="{{ $settings['student_info_image'] }}" alt="Student info background">
        @endisset
        <div class="@if(($settings['student_info_image'] ?? false) && ($settings['student_info_overlay'] ?? false)) card-img-overlay @endif p-5 d-flex align-items-center" style="background-color:{{ $settings['primary_color'] ?? '#008542' }}e0">
            <div class="lead">
                {!! $settings['student_info'] ?? 'Use the links in the next step to learn about faculty research here.' !!}
            </div>
        </div>
    </div>

    <div class="row">
        
        <div class="col-md-4 offset-md-1">
            <h2><i class="fas fa-search"></i> Explore</h2>
            <p>Bookmark research projects and opportunities by exploring {{ $settings['site_title'] ?? 'this site' }}:</p>
            <p><a class="btn btn-primary" href="{{ route('tags.index') }}" target="_blank" role="button">Explore research topics <i class="fas fa-arrow-right"></i></a></p>
            <p><a class="btn btn-primary" href="{{ route('profiles.index') }}" target="_blank" role="button">Browse research profiles <i class="fas fa-arrow-right"></i></a></p>
        </div>

        <div class="col-md-2">
            <h2 class="d-none d-md-block text-center"><i class="fas fa-arrow-right"></i></h2>
            <h2 class="d-md-none text-center"><i class="fas fa-arrow-down"></i></h2>
        </div>
        
        <div class="col-md-4">
            <h2><i class="fas fa-hands-helping"></i> Apply</h2>
            <p>Ready to reach out? Create an application here:</p>
            <p><a class="btn btn-primary" href="{{ route('students.create') }}" role="button">Apply to join a research lab <i class="fas fa-arrow-right"></i></a></p>
        </div>
    </div>
</div>

@stop