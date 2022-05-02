@extends('layout')
@section('title', 'All Student Research Applications')
@section('header')
	@include('nav')
@stop
@section('content')

<div class="container">

    <h1>All Student Research Applications</h1>

    <livewire:students-table>

</div>

@stop
