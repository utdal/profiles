@extends('layout')
@section('title', 'All Tags Table')
@section('header')
    @include('nav')
    @push('breadcrumbs')
        <li class="breadcrumb-item active">
            Admin
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            All Tags
        </li>
    @endpush
    @include('breadcrumbs')
@stop
@section('content')

<div class="container">
    <h1><i class="fas fa-tags" aria-hidden="true"></i> All Tags</h1>

    <livewire:tags-table>

</div>

@stop