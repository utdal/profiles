@extends('layouts.confirm')

@section('confirm_title')
    Confirm Restore Profile
@stop

@section('form')
    <form action="{{ route('profiles.restore', ['profile' => $profile]) }}" method="POST" accept-charset="UTF-8">
        @csrf
        <p>Are you sure you want to restore the profile <strong>{{ $profile->full_name }}</strong>?</p>
        <button type="button" class="btn btn-light" id="cancel">
            <i class="fas fa-fw fa-times"></i> Cancel
        </button>
        <button type="submit" class="btn btn-danger">
            <i class="fas fa-trash-restore"></i> Restore
        </button>
    </form>
@stop