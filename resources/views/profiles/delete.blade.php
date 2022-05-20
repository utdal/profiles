@extends('layouts.confirm-delete')

@section('cancel_button')
	<button type="button" class="btn btn-light" id="cancel">
		<i class="fas fa-fw fa-times"></i> Cancel
	</button>
@stop

@section('form')
	@if (!$profile->trashed())
		@section('delete_title')
			Confirm Archive Profile
		@stop
		{!! Form::open(['route' => ['profiles.archive', $profile ], 'method' => 'DELETE']) !!}
			<p>Are you sure to archive the profile of <strong>{{ $profile->full_name }}</strong>?</p>
				@yield('cancel_button')
				<button type="submit" class="btn btn-danger">
					<i class="fas fa-fw fa-trash"></i> Archive
				</button>
		{!! Form::close() !!}
	@else
		@section('delete_title')
			Confirm Restore Profile
		@stop
		{!! Form::open(['route' => ['profiles.restore', $profile ], 'method' => 'POST']) !!}
			<p>Are you sure to restore the profile of <strong>{{ $profile->full_name }}</strong>?</p>
				@yield('cancel_button')
				<button type="submit" class="btn btn-danger">
					<i class="fas fa-trash-restore"></i> Restore
				</button>
		{!! Form::close() !!}
	@endif
@stop