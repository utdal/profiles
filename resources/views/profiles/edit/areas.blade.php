<h1>Edit <a href="{{ action('ProfilesController@show', [$profile->slug]) }}">{{$profile->name}}</a>'s Research Areas</h1>

{!! Form::open(['url' => route('profiles.update', [$profile->slug, 'areas'])]) !!}
	<div class="sortable">
		@foreach($data as $area)
			<div class="row record form-group level lower-border">
				<div class="actions">
					<a class="handle" title="Sort"><i class="fas fa-arrows-alt-v"></i></a>
					<a class="trash" title="Clear"><i class="fas fa-times"></i></a>
				</div>
				<div class="col col-lg-3 col-12">
					<input type="hidden" name="data[{{$area->id}}][id]"  value="{{$area->id}}" />
					<label for="data[{{$area->id}}][data][title]">Title</label>
					<textarea class="form-control" rows="4" id="data[{{$area->id}}][data][title]" name="data[{{$area->id}}][data][title]">{{$area->title}}</textarea>
				</div>
				<div class="col col-lg-8 col-12">
					<label for="data[{{$area->id}}][data][description]">Description</label>
					<input id="rte_{{$area->id}}" type="hidden" class="clearable" id="data[{{$area->id}}][data][description]" name="data[{{$area->id}}][data][description]" value="{{$area->description}}">
					<trix-editor input="rte_{{$area->id}}"></trix-editor>
				</div>
			</div>
		@endforeach
	</div>
	@include('profiles.edit._buttons')

{!! Form::close() !!}
