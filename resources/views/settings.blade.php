@extends('layout')
@section('title', 'Edit Settings')
@section('header')
  @include('nav')
@stop

@section('content')

@include('errors/list')
<div class="container">
    <h2>Edit Site Settings</h2>

    <div class="row lower-border">
        <div class="col col-md-4">
        {!! Form::open(['url' => route('app.settings.update-image', 'logo'), 'method' => 'POST', 'files' => true]) !!}
        <label for="file">Icon</label>
        @if(isset($settings['logo']))
            <img id="file-img" class="profile_photo" src="{{ $settings['logo'] }}" style="background-color:{{ $settings['primary_color'] ?? '#008542' }};padding:10px;" />
        @endif
        <br />
        <br />
        <div class="control-group">
            <div class="controls">
                {!! Form::file('image', ['id' => 'file', 'name' => 'image', 'required' => 'true', 'accept' => 'image/*', 'class' => 'd-none']) !!}
                <label for="file" class="btn btn-secondary btn-block"><i class="fas fa-plus"></i> Select Image</label>
                <p class="errors">{!!$errors->first('image')!!}</p>
                @if(Session::has('error'))
                    <p class="errors">{!! Session::get('error') !!}</p>
                @endif
            </div>
        </div>
        <button type="submit" class="btn btn-primary btn-block" data-toggle="replace-icon" data-newicon="sync" data-newiconclasses="fa-spin" data-inputrequired="#file">
            <i class="fas fa-upload"></i> Replace Image
        </button>
        {!! Form::close() !!}
        </div>
    </div>
    {!! Form::open(['url' => route('app.settings.update')]) !!}

    <div class="row record form-group level lower-border">
        <div class="col col-md-4 col-12">
            <label for="primary_color">Primary Color (e.g #C75B12)</label>
            <input type="text" class="form-control" id="primary_color" name="setting[primary_color]" pattern="#[A-Za-z0-9]{6}" value="{{ $settings['primary_color'] ?? NULL }}" />
        </div>
        <div class="col col-md-4 col-12">
            <label for="secondary_color">Secondary Color (e.g #C75B12)</label>
            <input type="text" class="form-control" id="secondary_color" name="setting[secondary_color]" pattern="#[A-Za-z0-9]{6}" value="{{ $settings['secondary_color'] ?? NULL }}" />
        </div>
                <div class="col col-md-4 col-12">
            <label for="tertiary_color">Tertiary Color (e.g #C75B12)</label>
            <input type="text" class="form-control" id="tertiary_color" name="setting[tertiary_color]" pattern="#[A-Za-z0-9]{6}" value="{{ $settings['tertiary_color'] ?? NULL }}" />
        </div>
    </div>

    <div class="row record form-group level lower-border">
        <div class="col col-md-12 col-12">
            <label for="tertiary_color">Site Title</label>
            <span>for constructing page titles.</span>
            <input type="text" class="form-control" id="site_title" name="setting[site_title]" value="{{ $settings['site_title'] ?? NULL }}" />
        </div>
    </div>

    <div class="row record form-group level lower-border">
        <div class="col col-12">
            <label for="rte_description">Site Description</label>
            <input id="rte_description" type="hidden" class="clearable" name="setting[description]" value="{{ $settings['description'] ?? NULL }}">
            <trix-editor input="rte_description"></trix-editor>
        </div>
    </div>

    <div class="row record form-group level lower-border">
        <div class="col col-12">
            <label for="rte_faq">FAQ Page</label>
            <input id="rte_faq" type="hidden" class="clearable" name="setting[faq]" value="{{ $settings['faq'] ?? NULL }}">
            <trix-editor input="rte_faq"></trix-editor>
        </div>
    </div>

    <div class="row record form-group level lower-border">
        <div class="col col-12">
            <label for="rte_footer">Footer Footer</label>
            <input id="rte_footer" type="hidden" class="clearable" name="setting[footer]" value="{{ $settings['footer'] ?? NULL }}">
            <trix-editor input="rte_footer"></trix-editor>
        </div>
    </div>
   	
    <div class="row">
        <div class="col">
            {!! Form::submit('Save', array('class' => 'btn btn-primary edit-button')) !!}
            <a href="{{route('profiles.home')}}" class='btn btn-light edit-button'>Cancel</a>
        </div>
    </div>

    {!! Form::close() !!}
</div>
@stop