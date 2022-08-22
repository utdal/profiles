@extends('layout')
@section('title', 'Create a new tag')
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
            Add Tags
        </li>
    @endpush
    @include('breadcrumbs')
@stop
@section('content')

<div class="container">
    <h1><i class="fas fa-tags" aria-hidden="true"></i> Add Tags</h1>

    {!! Form::open(['route' => 'tags.store']) !!}

    <div class="mb-3">
        {!! Form::label('tag_name', 'Tag name(s)', ['class' => 'form-label']) !!}
        <small class="text-muted">One tag per line</small>
        {!! Form::textarea('tag_name', null, ['class' => 'form-control', 'required']) !!}
    </div>
    <div class="mb-3">
        {!! Form::label('tag_type', 'Tag type', ['class' => 'form-label']) !!}
        <small class="text-muted">e.g. App\Profile, App\Student, and etc.</small>
        {!! Form::text('tag_type', null, ['class' => 'form-control', 'required']) !!}
    </div>

    <button type="submit" class="btn btn-primary edit-button">Submit</button>
    <a href="{{ url()->previous() }}" class='btn btn-light edit-button'>Cancel</a>

    {!! Form::close() !!}

</div>

@stop