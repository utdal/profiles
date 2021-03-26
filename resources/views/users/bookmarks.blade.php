@extends('layout')
@section('title', 'User Bookmarks - ' . $user->display_name)
@section('header')
    @include('nav')
@stop
@section('content')
<div class="container">

    <h2><i class="fas fa-bookmark"></i> {{ $user->display_name }} Bookmarks</h2>

    <h3>Bookmarked Profiles</h3>
    <ul class="fa-ul">
    @forelse ($profile_bookmarks as $profile)
        <li>
            <span class="fa-li"><i class="fas fa-bookmark"></i></span>
            <a href="{{ $profile->url }}">{{ $profile->name }}</a>
        </li>
    @empty
        <li>
            <span class="fa-li"><i class="fas fa-bookmark"></i></span>
            No bookmarks. Visit a profile and click on the <button class="btn btn-primary btn-sm" disabled><i class="far fa-bookmark"></i> bookmark</button> button to bookmark it.
        </li> 
    @endforelse
    </ul>

</div>
@stop