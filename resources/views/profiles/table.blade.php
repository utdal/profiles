@extends('layout')
@section('title', 'All Profiles Table')
@section('header')
	@include('nav')
    @push('breadcrumbs')
        <li class="breadcrumb-item active">
            Admin
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            All Profiles
        </li>
    @endpush
    @include('breadcrumbs')
@stop
@section('content')
<div class="container">
    <h1>All Profiles</h1>

    <livewire:profiles-table>

</div>

@stop
