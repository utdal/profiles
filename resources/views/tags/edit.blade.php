@extends('layout')
@section('title', 'Edit tag - $tag->name')
@section('header')
    @include('nav')
    @push('breadcrumbs')
        <li class="breadcrumb-item active">
            Admin
        </li>
        @can('viewAdminIndex', Spatie\Tags\Tag::class)
            <li class="breadcrumb-item active">
                <a href="{{ route('tags.table') }}">All Tags</a>
            </li>
        @endcan
        <li class="breadcrumb-item active" aria-current="page">
            Edit Tag
        </li>
    @endpush
    @include('breadcrumbs')
@stop
@section('content')

<div class="container">
    <h1><i class="fas fa-tags" aria-hidden="true"></i> Edit Tag {{ $tag->name }}</h1>

    @include('errors/list')
    {{ html()->modelForm($tag, 'PATCH', route('tags.updateTag', $tag))->class('form-horizontal')->attribute('accept-charset', 'UTF-8')->open() }}
        <div class="form-group {{ ($errors->has('name') ?  'has-error' : '') }}">
            {{ html()->label('Tag name(s)', 'name')->class('col-sm-2 control-label') }}
            <div class="col-sm-9">
                <span class="text-danger">{!! $errors->first('name') !!}</span>
                {{ html()->text('name', null)->class('form-control') }}
            </div>
            <div class="col-sm-9">
                <span class="text-danger">{!! $errors->first('type') !!}</span>
                {{ html()->text('type', null)->isReadonly()->class('form-control') }}
            </div>
        </div>

        <!-- Submit Button -->
        <div class="col-sm-9">
            <a href="{{ route('tags.table') }}" class='btn btn-light'>Cancel</a>
            {{ html()->submit('Update Tag')->class('btn btn-primary') }}
        </div>
    {{ html()->closeModelForm() }}
</div>
@stop
