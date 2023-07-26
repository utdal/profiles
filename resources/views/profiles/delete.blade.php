@extends('layouts.confirm')

@section('confirm_title')
    Confirm Archive Profile
@stop

@section('form')
    <form action="{{ route('profiles.archive', ['profile' => $profile]) }}" method="POST" accept-charset="UTF-8">
        @csrf
        @method('DELETE')
        <p>Are you sure you want to archive the profile <strong>{{ $profile->full_name }}</strong>?</p>
        <button type="button" class="btn btn-light" id="cancel">
            <i class="fas fa-fw fa-times"></i> Cancel
        </button>
        <button type="submit" class="btn btn-danger">
            <i class="fas fa-fw fa-trash"></i> Archive
        </button>
    </form>
@stop