@extends('layout')

@section('title')
	@yield('delete_title')
@stop

@section('header')
  @include('nav')
@stop

@section('content')
	<div class="container">

		<div class="row text-center mt-5 d-flex justify-content-around">
			<div class="col-md-8">
				<div class="card bg-danger shadow-lg">
					<div class="card-header">
						<h3 class="text-white bold">@yield('delete_title')</h3>
					</div>
					<div class="card-body bg-white">
						@yield('form')
					</div>
				</div>
			</div>
		</div>
	</div>
@stop

@push('scripts')
	<script>
	$(document).ready( function () {

	$('#cancel').click(function() {
		window.history.back();
	});

	});
	</script>
@endpush