<h1>Edit <a href="{{ action('ProfilesController@show', [$profile->slug]) }}">{{$profile->name}}</a>'s Professional Preparation</h1>

{!! Form::open(['url' => route('profiles.update', [$profile->slug, 'preparation'])]) !!}
	<div class="sortable">
		@foreach($data as $prep)
			<div class="row record form-group lower-border">
				<div class="actions">
					<a class="handle" title="Sort"><i class="fas fa-arrows-alt-v"></i></a>
					<a class="trash" title="Clear"><i class="fas fa-times"></i></a>
				</div>
				<div class="col col-lg-3 col-sm-6 col-12">
					<input type="hidden" name="data[{{$prep->id}}][id]"  value="{{$prep->id}}" />
					<label for="data[{{$prep->id}}][data][degree]">Degree</label>
					<input type="text" class="form-control" id="data[{{$prep->id}}][data][degree]" name="data[{{$prep->id}}][data][degree]" value="{{$prep->degree}}" />
				</div>
				<div class="col col-lg-3 col-sm-6 col-12">
					<label for="data[{{$prep->id}}][data][major]">Major</label>
					<input type="text" class="form-control" id="data[{{$prep->id}}][data][major]" name="data[{{$prep->id}}][data][major]" value="{{$prep->major}}" />
				</div>
				<div class="col col-lg-3 col-sm-6 col-12">
					<label for="data[{{$prep->id}}][data][institution]">Institution</label>
					<input type="text" class="form-control" id="data[{{$prep->id}}][data][institution]" name="data[{{$prep->id}}][data][institution]" value="{{$prep->institution}}" />
				</div>
				<div class="col col-lg-3 col-sm-6 col-12">
					<label for="data[{{$prep->id}}][data][year]">Year</label>
					<input type="text" class="datepicker year form-control" id="data[{{$prep->id}}][data][year]" name="data[{{$prep->id}}][data][year]" value="{{$prep->year}}" pattern="^[0-9]{4}$"/>
				</div>
			</div>
		@endforeach
	</div>
	@include('profiles.edit._buttons')

{!! Form::close() !!}
