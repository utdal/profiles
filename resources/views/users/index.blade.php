@extends('layout')
@section('title', 'All Users Table')
@section('header')
    @include('nav')
    @push('breadcrumbs')
        <li class="breadcrumb-item active">
            Admin
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            All Users
        </li>
    @endpush
    @include('breadcrumbs')
@stop
@section('content')
<div class="container">
    <h1>All Users</h1>
    
    <livewire:users-table>

</div>
@stop
