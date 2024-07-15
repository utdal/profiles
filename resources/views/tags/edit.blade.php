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

    @include('errors/has')

    {!! Form::model($tag, ['method' => 'PATCH','route' => ['tags.updateTag', $tag], 'class' => 'form-horizontal' ]) !!}

        <div class="form-group {{ ($errors->has('name') ?  'has-error' : '') }}">
            {!! Form::label('name', 'Tag name(s)', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-9">
                <span class="text-danger">{!! $errors->first('name') !!}</span>
                {!! Form::text('name', null, ['class' => 'form-control']) !!}
            </div>
            <div class="col-sm-9">
                <span class="text-danger">{!! $errors->first('type') !!}</span>
                {!! Form::text('type', null, ['class' => 'form-control', 'readonly']) !!}
            </div>
        </div>

        <!-- Submit Button -->
        <div class="col-sm-9">
            <a href="{{ route('tags.table') }}" class='btn btn-light'>Cancel</a>
            {!! Form::submit('Update Tag', ['class' => 'btn btn-primary']) !!}
        </div>
    {!! Form::close() !!}
</div>
@stop
