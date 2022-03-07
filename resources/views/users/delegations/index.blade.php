@extends('layout')
@section('title', 'All Delegations Table')
@section('header')
	@include('nav')
@stop
@section('content')
<div class="container">
    <h1><i class="fas fa-user-friends"></i> All Delegations</h1>
    
    <livewire:delegations-table>

</div>
@stop
