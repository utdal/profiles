@extends('layout')
@section('title', 'All Users Table')
@section('header')
	@include('nav')
@stop
@section('content')
<div class="container">
    <h1>All Users</h1>
    
    <livewire:users-table>

</div>
@stop
