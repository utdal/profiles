<h1>Edit <a href="{{ action('ProfilesController@show', [$profile->slug]) }}">{{$profile->name}}</a>'s Presentations</h1>

{!! Form::open(['url' => route('profiles.update', [$profile->slug, 'presentations'])]) !!}
	<div class="sortable">
		@foreach($data as $pres)
		<div class="record lower-border">
			<div class="actions">
				<a class="handle" title="Sort"><i class="fas fa-arrows-alt-v"></i></a>
				<a class="trash" title="Clear"><i class="fas fa-times"></i></a>
			</div>
			<div class="row form-group level">
				<div class="col col-md-6 col-12">
					<input type="hidden" name="data[{{$pres->id}}][id]"  value="{{$pres->id}}" />
					<label for="data[{{$pres->id}}][data][title]">Title</label>
					<textarea class="form-control" rows="4" id="data[{{$pres->id}}][data][title]" name="data[{{$pres->id}}][data][title]">{{$pres->title}}</textarea>
				</div>
				<div class="col col-md-6 col-12">
					<label for="data[{{$pres->id}}][data][description]">Description</label>
					<input id="rte_{{$pres->id}}" type="hidden" class="clearable" id="data[{{$pres->id}}][data][description]" name="data[{{$pres->id}}][data][description]" value="{{$pres->description}}">
  				<trix-editor input="rte_{{$pres->id}}"></trix-editor>
				</div>
			</div>
			<div class="row form-group level">
				<div class="col col-md-4 col-12">
					<label for="data[{{$pres->id}}][data][url]">URL</label>
					<input type="url" class="form-control" id="data[{{$pres->id}}][data][url]" name="data[{{$pres->id}}][data][url]" value="{{$pres->url}}" />
				</div>
				<div class="col col-md-4 col-12">
					<label for="data[{{$pres->id}}][data][start_date]">Start Date</label>
					<input type="text" class="datepicker month form-control" id="data[{{$pres->id}}][data][start_date]" name="data[{{$pres->id}}][data][start_date]" value="{{$pres->start_date}}" pattern="^[0-9]{4}\/[0-9]{2}$" />
				</div>
				<div class="col col-md-4 col-12">
					<label for="data[{{$pres->id}}][data][end_date]">End Date</label>
					<input type="text" class="datepicker month form-control" id="data[{{$pres->id}}][data][end_date]" name="data[{{$pres->id}}][data][end_date]" value="{{$pres->end_date}}" pattern="^[0-9]{4}\/[0-9]{2}$" />
				</div>
			</div>
		</div>
		@endforeach
	</div>
	@include('profiles.edit._buttons')

{!! Form::close() !!}
