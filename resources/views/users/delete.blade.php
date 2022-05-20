@extends('layouts.confirm-delete')

@section('delete_title')
	Confirm Delete User
@stop

@section('form')
	{!! Form::open(['route' => ['users.delete', $user ], 'method' => 'DELETE']) !!}
		<p>Are you sure to delete the user of <strong>{{ $user->display_name }}</strong>?</p>
		<button type="button" class="btn btn-light" id="cancel">
			<i class="fas fa-fw fa-times"></i> Cancel
		</button>
			<button type="submit" class="btn btn-danger">
			<i class="fas fa-fw fa-trash"></i> Delete
		</button>
	{!! Form::close() !!}
@stop