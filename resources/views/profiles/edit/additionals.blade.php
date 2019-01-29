<h1>Edit <a href="{{ action('ProfilesController@show', [$profile->slug]) }}">{{$profile->name}}</a>'s Additional Information</h1>

{!! Form::open(['url' => route('profiles.update', [$profile->slug, 'additionals'])]) !!}
	<div class="sortable">
		@foreach($data as $add)
			<div class="row record form-group level lower-border">
				<div class="actions">
					<a class="handle" title="Sort"><i class="fas fa-arrows-alt-v"></i></a>
					<a class="trash" title="Clear"><i class="fas fa-times"></i></a>
				</div>
				<div class="col col-lg-3 col-12">
					<input type="hidden" name="data[{{$add->id}}][id]"  value="{{$add->id}}" />
					<label for="data[{{$add->id}}][data][title]">Title</label>
					<textarea class="form-control" rows="4" id="data[{{$add->id}}][data][title]" name="data[{{$add->id}}][data][title]">{{$add->title}}</textarea>
				</div>
				<div class="col col-lg-7 col-12">
					<label for="data[{{$add->id}}][data][description]">Description</label>
					<input id="rte_{{$add->id}}" type="hidden" class="clearable" name="data[{{$add->id}}][data][description]" value="{{$add->description}}">
  				<trix-editor input="rte_{{$add->id}}"></trix-editor>
				</div>
				<div class="col col-lg-2 col-12">
					<label for="data[{{$add->id}}][data][url]">URL</label>
					<input type="url" class="form-control" id="data[{{$add->id}}][data][url]" name="data[{{$add->id}}][data][url]" value="{{$add->url}}" />
				</div>
			</div>
		@endforeach
	</div>
	@include('profiles.edit._buttons')

{!! Form::close() !!}
