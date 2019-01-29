@extends('layout')
@section('title', 'Confirm Deletion of User')
@section('header')
  @include('nav')
@stop

@section('content')
<div class="container">
	@include('errors/list')

	<h2 class="text-center">Confirm Deletion of User:</h2>
	<h2 class="text-center">{{ $user->display_name }} ({{ $user->name }})</h2>

	<div class="row text-center d-flex justify-content-around">
		<div class="col-md-8">
			<div class="card bg-danger">
				<div class="card-header">
					<h4 class="text-white">Are you sure?</h4>
				</div>
				<div class="card-body bg-white">
				{!! Form::open(['route' => ['users.destroy', $user->pea], 'method' => 'DELETE']) !!}
					<button type="button" class="btn btn-light" id="cancel">
						<i class="fas fa-fw fa-times"></i> Cancel
					</button>
						<button type="submit" class="btn btn-danger">
						<i class="fas fa-fw fa-trash"></i> Delete
					</button>
				{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
</div>
@stop

@section('scripts')
<script>
$(document).ready( function () {

  $('#cancel').click(function() {
    window.history.back();
  });

});
</script>
@stop