@extends('layout')
@section('title', 'Edit Schools')
@section('header')
  @include('nav')
@stop

@section('content')

@include('errors/list')
<div class="container">
    <h2 class="text-center mt-5">Edit School &ldquo;{{ $school->display_name }}&rdquo;:</h2>

    {!! Form::model($school, ['method' => 'PATCH', 'route' => ['schools.update', $school], 'class' => 'form-horizontal', 'id' => 'schools_form']) !!}
        @include('schools.form', ['submitButtonText' => 'Update School'])
    {!! Form::close() !!}

</div>
@stop