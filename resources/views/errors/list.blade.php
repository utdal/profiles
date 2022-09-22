@if ($errors->any())
<div class="row">
	<div class="alert alert-danger alert-dismissable col-sm-offset-2 col-sm-9" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<p><strong>There are some errors. Please correct them and try again.</strong></p>
		@foreach ($errors->all() as $error)
			{{ $error }}<br>
		@endforeach
	</div>
</div>
@endif