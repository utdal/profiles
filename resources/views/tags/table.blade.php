@extends('layout')
@section('title', 'All Tags Table')
@section('header')
    @include('nav')
@stop
@section('content')

<div class="container">
    <h1><i class="fas fa-tags" aria-hidden="true"></i> All Tags</h1>

    <livewire:tags-table>

</div>

@stop