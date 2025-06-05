@extends('layout')
@section('title', 'Edit Student Research Application')
@section('header')
	@include('nav')
    @push('breadcrumbs')
        <li class="breadcrumb-item">
            <a href="{{ route('students.about') }}">Student Research</a>
        </li>
        <li class="breadcrumb-item active">
            @can('viewAny', App\Student::class)
                <a href="{{ route('students.index') }}">All Applications</a>
            @else
                Applications
            @endcan
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('students.show', ['student' => $student]) }}">{{ $student->full_name }}</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            Edit
        </li>
    @endpush
    @include('breadcrumbs')
@stop
@section('content')

<div class="container">

    <h1 class="mb-0">Student Research Application</h1>
    <h2 class="mt-0 text-muted">for {{ $student->full_name }}</h2>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissable" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <p><strong>There are some errors. Please correct them and try again.</strong></p>
            @foreach ($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
        </div>
    @endif

    <div class="alert alert-success" role="alert">
        <p class="mb-0">Complete and submit your student research application below. This application will not be public, but will be made available to faculty researchers who may be looking for students. After submitting, you can always come back later to edit or withdraw your student research application.</p>
    </div>

    {!! Form::model($student, ['route' => ['students.update', $student]]) !!}
        @include('students.form', ['editable' => true])
    {!! Form::close() !!}

</div>

@stop

@push('scripts')
<script>
    document.addEventListener('livewire:load', function () {
        $("input[type=checkbox][id^=data_school]").on('change', function() {
            if ($(this).is(':checked')) {
                Livewire.emit('addTagType', "App\\Student\\"+$(this).val());
            } else {
                Livewire.emit('removeTagType', "App\\Student\\"+$(this).val());
            }
        })
    });
</script>
@endpush