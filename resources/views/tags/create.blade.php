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

    @include('errors/list')
    {{ html()->form('POST', route('tags.store'))->attribute('accept-charset', 'UTF-8')->open() }}
    
    <div class="mb-3">
        {{ html()->label('Tag name(s)', 'name')->class('form-label') }}
        <small class="text-muted">One tag per line</small>
        {{ html()->textarea('name', null)->required()->class('form-control')->attributes(['rows' => '10', 'cols' => '50']) }}
    </div>
    <div class="mb-3">
        {{ html()->label('Tag type', 'type')->class('form-label') }}
        <small class="text-muted">e.g. App\Profile, App\Student, and etc.</small>
        {{ html()->text('type', null)->required()->class('form-control') }}
    </div>
    
    <button type="submit" class="btn btn-primary edit-button">Submit</button>
    <a href="{{ url()->previous() }}" class='btn btn-light edit-button'>Cancel</a>
    
    {{ html()->form()->close() }}

</div>

@stop