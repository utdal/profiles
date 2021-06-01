@extends('layout')
@section('title', 'All Profiles Table')
@section('header')
	@include('nav')
@stop
@section('content')
<div class="container">
    <h1>All Profiles</h1>

    <livewire:profiles-table>

</div>

@stop
