@extends('layouts.confirm')

@section('confirm_title')
    Confirm Delete User
@stop

@section('form')
    <form action="{{ route('users.delete', ['user' => $user]) }}" method="POST" accept-charset="UTF-8">
        @csrf
        @method('DELETE')
        <p>Are you sure you want permanently to delete the user of <strong>{{ $user->display_name }}</strong>?</p>
        <p>This action will also delete the user's profile if they have one.</p>
        <button type="button" class="btn btn-light" id="cancel">
            <i class="fas fa-fw fa-times"></i> Cancel
        </button>
            <button type="submit" class="btn btn-danger">
            <i class="fas fa-fw fa-trash"></i> Delete
        </button>
    </form>
@stop