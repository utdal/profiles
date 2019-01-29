<h1>Edit <a href="{{ action('ProfilesController@show', [$profile->slug]) }}">{{$profile->name}}</a>'s Funding</h1>

{!! Form::open(['url' => route('profiles.update', [$profile->slug, 'support'])]) !!}
	<div class="sortable">
		@foreach($data as $support)
		<div class="record lower-border">
			<div class="actions">
				<a class="handle" title="Sort"><i class="fas fa-arrows-alt-v"></i></a>
				<a class="trash" title="Clear"><i class="fas fa-times"></i></a>
			</div>
			<div class="row form-group level">
				<div class="col col-md-6 col-12">
					<input type="hidden" name="data[{{$support->id}}][id]"  value="{{$support->id}}" />
					<label for="data[{{$support->id}}][data][title]">Title</label>
					<textarea class="form-control" rows="4" id="data[{{$support->id}}][data][title]" name="data[{{$support->id}}][data][title]">{{$support->title}}</textarea>
				</div>
				<div class="col col-md-3 col-12">
					<label for="data[{{$support->id}}][data][sponsor]">Sponsor</label>
					<textarea class="form-control" rows="4" id="data[{{$support->id}}][data][sponsor]" name="data[{{$support->id}}][data][sponsor]">{{$support->sponsor}}</textarea>
				</div>
				<div class="col col-md-3 col-12">
					<label for="data[{{$support->id}}][data][amount]">Amount</label>
					<input type="text" class="form-control" id="data[{{$support->id}}][data][amount]" name="data[{{$support->id}}][data][amount]" value="{{$support->amount}}" />
				</div>
			</div>
			<div class="row form-group level">
				<div class="col col-md-6 col-12">
					<label for="data[{{$support->id}}][data][description]">Description</label>
					<textarea class="form-control" rows="2" id="data[{{$support->id}}][data][description]" name="data[{{$support->id}}][data][description]">{{$support->description}}</textarea>
				</div>
				<div class="col col-md-3 col-12">
					<label for="data[{{$support->id}}][data][start_date]">Start Date</label>
					<input type="text" class="datepicker month form-control" id="data[{{$support->id}}][data][start_date]" name="data[{{$support->id}}][data][start_date]" value="{{$support->start_date}}" pattern="^[0-9]{4}\/[0-9]{2}$"/>
				</div>
				<div class="col col-md-3 col-12">
					<label for="data[{{$support->id}}][data][end_date]">End Date</label>
					<input type="text" class="datepicker month form-control" id="data[{{$support->id}}][data][end_date]" name="data[{{$support->id}}][data][end_date]" value="{{$support->end_date}}" pattern="^[0-9]{4}\/[0-9]{2}$"/>
				</div>
			</div>
			</div>
		@endforeach
	</div>
	@include('profiles.edit._buttons')

{!! Form::close() !!}
