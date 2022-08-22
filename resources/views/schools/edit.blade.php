@extends('layout')
@section('title', 'Edit Schools')
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
        <li class="breadcrumb-item active">
            {{ $school->display_name }}
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            Edit
        </li>
    @endpush
    @include('breadcrumbs')
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