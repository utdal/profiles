@extends('layout')
@section('title', 'All Delegations Table')
@section('header')
	@include('nav')
    @push('breadcrumbs')
        <li class="breadcrumb-item active">
            Admin
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            All Delegations
        </li>
    @endpush
    @include('breadcrumbs')
@stop
@section('content')
<div class="container">
    <h1><i class="fas fa-user-friends"></i> All Delegations</h1>
    
    <livewire:delegations-table>

</div>
@stop
