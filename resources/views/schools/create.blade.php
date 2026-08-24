@extends('layout')
@section('title', 'Add a New School')
@section('header')
    @include('nav')
    @push('breadcrumbs')
        <li class="breadcrumb-item active">
            Admin
        </li>
        <li class="breadcrumb-item">
            @can('viewAdminIndex', App\School::class)
                <a href="{{ route('schools.index') }}">All Schools</a>
            @else
                All Schools
            @endcan
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            Add a New School
        </li>
    @endpush
    @include('breadcrumbs')
@stop

@section('content')

@include('errors/list')
<div class="container">
    <h2 class="text-center mt-5">Add a New School:</h2>

    {{ html()->form('POST', route('schools.store'))->id('schools_form')->class('form-horizontal')->attribute('accept-charset', 'UTF-8')->open() }}
        @include('schools.form', ['submitButtonText' => 'Add School'])
    {{ html()->form()->close() }}

</div>
@stop
