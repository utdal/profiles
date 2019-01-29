<h1>Edit <a href="{{ action('ProfilesController@show', [$profile->slug]) }}">{{$profile->name}}</a>'s Appointments</h1>

{!! Form::open(['url' => route('profiles.update', [$profile->slug, 'appointments'])]) !!}
	<div class="sortable">
		@foreach($data as $prep)
		<div class="record lower-border">
			<div class="actions">
				<a class="handle" title="Sort"><i class="fas fa-arrows-alt-v"></i></a>
				<a class="trash" title="Clear"><i class="fas fa-times"></i></a>
			</div>
			<div class="row form-group level">
				<div class="col col-lg-6 col-12">
					<input type="hidden" name="data[{{$prep->id}}][id]"  value="{{$prep->id}}" />
					<label for="data[{{$prep->id}}][data][appointment]">Appointment</label>
					<input type="text" class="form-control" id="data[{{$prep->id}}][data][appointment]" name="data[{{$prep->id}}][data][appointment]" value="{{$prep->appointment}}" />
				</div>
				<div class="col col-lg-6 col-12">
					<label for="data[{{$prep->id}}][data][organization]">Organization</label>
					<input type="text" class="form-control" id="data[{{$prep->id}}][data][organization]" name="data[{{$prep->id}}][data][organization]" value="{{$prep->organization}}" />
				</div>
			</div>
			<div class="row form-group level">
				<div class="col col-lg-6 col-12">
					<label for="data[{{$prep->id}}][data][description]">Description</label>
					<textarea class="form-control" rows="4" id="data[{{$prep->id}}][data][description]" name="data[{{$prep->id}}][data][description]">{{$prep->description}}</textarea>
				</div>
				<div class="col col-lg-3 col-12">
					<label for="data[{{$prep->id}}][data][start_date]">Start Date</label>
					<input type="text" class="datepicker year form-control" id="data[{{$prep->id}}][data][start_date]" name="data[{{$prep->id}}][data][start_date]" value="{{$prep->start_date}}" pattern="^[0-9]{4}$" />
				</div>
				<div class="col col-lg-3 col-12">
					<label for="data[{{$prep->id}}][data][end_date]">End Date</label>
					<input type="text" class="datepicker year form-control" id="data[{{$prep->id}}][data][end_date]" name="data[{{$prep->id}}][data][end_date]" value="{{$prep->end_date}}" pattern="^[0-9]{4}$" />
				</div>
			</div>
		</div>
		@endforeach
	</div>
	@include('profiles.edit._buttons')

{!! Form::close() !!}
