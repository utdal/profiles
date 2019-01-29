<h1>Edit <a href="{{ action('ProfilesController@show', [$profile->slug]) }}">{{$profile->name}}</a>'s Projects</h1>

{!! Form::open(['url' => route('profiles.update', [$profile->slug, 'projects'])]) !!}
	<div class="sortable">
		@foreach($data as $proj)
		<div class="record lower-border">
			<div class="actions">
				<a class="handle" title="Sort"><i class="fas fa-arrows-alt-v"></i></a>
				<a class="trash" title="Clear"><i class="fas fa-times"></i></a>
			</div>
			<div class="row form-group level">
				<div class="col col-md-6 col-12">
					<input type="hidden" name="data[{{$proj->id}}][id]"  value="{{$proj->id}}" />
					<label for="data[{{$proj->id}}][data][title]">Title</label>
					<textarea class="form-control" rows="4" id="data[{{$proj->id}}][data][title]" name="data[{{$proj->id}}][data][title]">{{$proj->title}}</textarea>
				</div>
				<div class="col col-md-6 col-12">
					<label for="data[{{$proj->id}}][data][description]">Description</label>
					<input id="rte_{{$proj->id}}" type="hidden" class="clearable" id="data[{{$proj->id}}][data][description]" name="data[{{$proj->id}}][data][description]" value="{{$proj->description}}">
  				<trix-editor input="rte_{{$proj->id}}"></trix-editor>
				</div>
			</div>
			<div class="row form-group level">
				<div class="col col-md-4 col-12">
					<label for="data[{{$proj->id}}][data][url]">URL</label>
					<input type="url" class="form-control" id="data[{{$proj->id}}][data][url]" name="data[{{$proj->id}}][data][url]" value="{{$proj->url}}" />
				</div>
				<div class="col col-md-4 col-12">
					<label for="data[{{$proj->id}}][data][start_date]">Start Date</label>
					<input type="text" class="datepicker month form-control" id="data[{{$proj->id}}][data][start_date]" name="data[{{$proj->id}}][data][start_date]" value="{{$proj->start_date}}" pattern="^[0-9]{4}\/[0-9]{2}$" />
				</div>
				<div class="col col-md-4 col-12">
					<label for="data[{{$proj->id}}][data][end_date]">End Date</label>
					<input type="text" class="datepicker month form-control" id="data[{{$proj->id}}][data][end_date]" name="data[{{$proj->id}}][data][end_date]" value="{{$proj->end_date}}" pattern="^[0-9]{4}\/[0-9]{2}$" />
				</div>
			</div>
		</div>
		@endforeach
	</div>
	@include('profiles.edit._buttons')

{!! Form::close() !!}
