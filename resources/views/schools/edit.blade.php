@extends('layout')
@section('title', 'Edit Schools')
@section('header')
  @include('nav')
@stop

@section('content')

@include('errors/list')
<div class="container">
    <h2>Edit Schools</h2>

    {!! Form::open(['url' => route('schools.update'), 'method' => 'POST']) !!}

    @foreach($schools as $school)

        <div class="row record form-group level lower-border">
            <div class="col col-md-6 col-12">
                <label for="schools[{{$school->id}}][name]">School Name</label>
                <input type="text" class="form-control" id="schools[{{$school->id}}][name]" name="schools[{{$school->id}}][name]" value="{{ $school->name }}" />
            </div>
            <div class="col col-md-6 col-12">
                <label for="schools[{{$school->id}}][display_name]">Display Name</label>
                <input type="text" class="form-control" id="schools[{{$school->id}}][display_name]" name="schools[{{$school->id}}][display_name]" value="{{ $school->display_name }}" />
            </div>            
            <div class="col col-md-6 col-12">
                <label for="schools[{{$school->id}}][short_name]">Short Name</label>
                <span>for use in URLs.</span>            
                <input type="text" class="form-control" id="schools[{{$school->id}}][short_name]" name="schools[{{$school->id}}][short_name]" value="{{ $school->short_name }}" />
            </div>            
            <div class="col col-md-6 col-12">
                <label for="schools[{{$school->id}}][aliases]">Aliases</label>
                <span>for constructing use in associating profiles (semicolon delimited).</span>                
                <input type="text" class="form-control" id="schools[{{$school->id}}][aliases]" name="schools[{{$school->id}}][aliases]" value="{{ $school->aliases }}" />
            </div>            
        </div>

    @endforeach

    <div class="row">
        <div class="col">
            {!! Form::submit('Save', array('class' => 'btn btn-primary edit-button')) !!}
            <a href="{{route('profiles.home')}}" class='btn btn-light edit-button'>Cancel</a>
        </div>
    </div>

    {!! Form::close() !!}
</div>
@stop