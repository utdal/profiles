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

    {!! Form::open(['route' => 'schools.store', 'class' => 'form-horizontal', 'id' => 'schools_form']) !!}
        @include('schools.form', ['submitButtonText' => 'Add School'])
    {!! Form::close() !!}

</div>
@stop
