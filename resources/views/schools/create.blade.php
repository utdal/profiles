@extends('layout')
@section('title', 'Add a New User')
@section('header')
  @include('nav')
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
