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
            <label for="logo">Logo</label>
            @if(isset($settings['logo']))
                <img id="logo-img" class="profile_photo p-2 border mb-3" src="{{ $settings['logo'] }}" style="background-color:{{ $settings['primary_color'] ?? '#008542' }};">
            @endif
            <div class="control-group">
                <div class="controls">
                    {!! Form::file('logo', ['id' => 'logo', 'name' => 'logo', 'required' => 'true', 'accept' => 'image/*', 'class' => 'd-none']) !!}
                    <label for="logo" class="btn btn-secondary btn-block"><i class="fas fa-plus"></i> Select Image</label>
                    <p class="errors">{!!$errors->first('logo')!!}</p>
                    @if(Session::has('error'))
                        <p class="errors">{!! Session::get('error') !!}</p>
                    @endif
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-block" data-toggle="replace-icon" data-newicon="fas fa-sync fa-spin" data-inputrequired="#logo">
                <i class="fas fa-upload"></i> Replace Image
            </button>
            {!! Form::close() !!}
        </div>
        <div class="col col-md-4">
            {!! Form::open(['url' => route('app.settings.update-image', 'favicon'), 'method' => 'POST', 'files' => true]) !!}
            <label for="favicon">Favicon</label>
            @if(isset($settings['favicon']))
                <img id="favicon-img" class="profile_photo p-2 border mb-3" src="{{ $settings['favicon'] }}">
            @endif
            <div class="control-group">
                <div class="controls">
                    {!! Form::file('favicon', ['id' => 'favicon', 'name' => 'favicon', 'required' => 'true', 'accept' => 'image/*', 'class' => 'd-none']) !!}
                    <label for="favicon" class="btn btn-secondary btn-block"><i class="fas fa-plus"></i> Select Image</label>
                    <p class="errors">{!!$errors->first('favicon')!!}</p>
                    @if(Session::has('error'))
                        <p class="errors">{!! Session::get('error') !!}</p>
                    @endif
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-block" data-toggle="replace-icon" data-newicon="fas fa-sync fa-spin" data-inputrequired="#favicon">
                <i class="fas fa-upload"></i> Replace Image
            </button>
            {!! Form::close() !!}
        </div>
        @if(config('app.enable_students'))
        <div class="col col-md-4">
            {!! Form::open(['url' => route('app.settings.update-image', 'student_info_image'), 'method' => 'POST', 'files' => true]) !!}
            <label for="student_info_image">Student Info Image</label>
            @if(isset($settings['student_info_image']))
                <img id="student_info_image-img" class="profile_photo p-2 border mb-3" src="{{ $settings['student_info_image'] }}">
            @endif
            <div class="control-group">
                <div class="controls">
                    {!! Form::file('student_info_image', ['id' => 'student_info_image', 'name' => 'student_info_image', 'required' => 'true', 'accept' => 'image/*', 'class' => 'd-none']) !!}
                    <label for="student_info_image" class="btn btn-secondary btn-block"><i class="fas fa-plus"></i> Select Image</label>
                    <p class="errors">{!!$errors->first('student_info_image')!!}</p>
                    @if(Session::has('error'))
                        <p class="errors">{!! Session::get('error') !!}</p>
                    @endif
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-block" data-toggle="replace-icon" data-newicon="fas fa-sync fa-spin" data-inputrequired="#student_info_image">
                <i class="fas fa-upload"></i> Replace Image
            </button>
            {!! Form::close() !!}
        </div>
        @endif
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
            <label for="rte_account_name">Institution User Account Name</label>
            <input id="rte_account_name" type="text" class="form-control" name="setting[account_name]" placeholder="MyInstitutionID" value="{{ $settings['account_name'] ?? NULL }}">
        </div>
    </div>

    <div class="row record form-group level lower-border">
        <div class="col col-12">
            <label for="rte_forgot_password_url">Forgot Password URL</label>
            <input id="rte_forgot_password_url" type="url" class="form-control" name="setting[forgot_password_url]" placeholder="https://id.example.com" value="{{ $settings['forgot_password_url'] ?? NULL }}">
        </div>
    </div>

    <div class="row record form-group level lower-border">
        <div class="col col-12">
            <label for="visibility">School Search Shortcut</label><br>
            <p><small class="form-text text-muted">If a search exactly matches a single school name, display name, or short name, skip the search results and redirect to that school's profiles instead.</small></p>
            <label class="switch pull-left">
                <input type="hidden" name="setting[school_search_shortcut]" id="setting[school_search_shortcut]" value="0">
                <input type="checkbox" name="setting[school_search_shortcut]" id="setting[school_search_shortcut]" value="1" @if($settings['school_search_shortcut'] ?? false) checked @endif>
                <span class="slider round"></span>
            </label>
        </div>
    </div>

    <div class="row record form-group level lower-border">
        <div class="col col-12">
            <label for="visibility">Profile Search Shortcut</label><br>
            <p><small class="form-text text-muted">If a search exactly matches a single profile display name, skip the search results and redirect to that profile instead.</small></p>
            <label class="switch pull-left">
                <input type="hidden" name="setting[profile_search_shortcut]" id="setting[profile_search_shortcut]" value="0">
                <input type="checkbox" name="setting[profile_search_shortcut]" id="setting[profile_search_shortcut]" value="1" @if($settings['profile_search_shortcut'] ?? false) checked @endif>
                <span class="slider round"></span>
            </label>
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
            <label for="rte_footer">Site Footer</label>
            <input id="rte_footer" type="hidden" class="clearable" name="setting[footer]" value="{{ $settings['footer'] ?? NULL }}">
            <trix-editor input="rte_footer"></trix-editor>
        </div>
    </div>

    @if(config('app.enable_students'))
    <div class="row record form-group level lower-border">
        <div class="col col-12 col-lg-9">
            <label for="rte_student_info">Student Info</label>
            <input id="rte_student_info" type="hidden" class="clearable" name="setting[student_info]" value="{{ $settings['student_info'] ?? NULL }}">
            <trix-editor input="rte_student_info"></trix-editor>
        </div>
        <div class="col col-12 col-lg-3">
            <label for="visibility">Overlay student info over image</label><br>
            <label class="switch pull-left">
                <input type="hidden" name="setting[student_info_overlay]" id="setting[student_info_overlay]" value="0">
                <input type="checkbox" name="setting[student_info_overlay]" id="setting[student_info_overlay]" value="1" @if($settings['student_info_overlay'] ?? false) checked @endif>
                <span class="slider round"></span>
            </label>
        </div>
    </div>
    @endif
   	
    <div class="row">
        <div class="col">
            {!! Form::submit('Save', array('class' => 'btn btn-primary edit-button')) !!}
            <a href="{{route('profiles.home')}}" class='btn btn-light edit-button'>Cancel</a>
        </div>
    </div>

    {!! Form::close() !!}
</div>
@stop