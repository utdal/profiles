<h1>Edit <a href="{{ action('ProfilesController@show', [$profile->slug]) }}">{{$profile->name}}</a>'s Activities</h1>

{!! Form::open(['url' => route('profiles.update', [$profile->slug, 'activities'])]) !!}
	<div class="sortable">
		@foreach($data as $activity)
			<div class="row record form-group level lower-border">
				<div class="col col-lg-4 col-12">
					<input type="hidden" name="data[{{$activity->id}}][id]"  value="{{$activity->id}}" />
					<label for="data[{{$activity->id}}][data][title]">Title</label>
					<textarea class="form-control" rows="4" id="data[{{$activity->id}}][data][title]" name="data[{{$activity->id}}][data][title]">{{$activity->title}}</textarea>
				</div>
				<div class="col col-lg-8 col-12">
					<label for="rte_{{$activity->id}}">Description</label>
					<input id="rte_{{$activity->id}}" type="hidden" class="clearable" name="data[{{$activity->id}}][data][description]" value="{{$activity->description}}">
					<trix-editor input="rte_{{$activity->id}}"></trix-editor>
				</div>
				<div class="actions">
					<a class="handle" title="Sort"><i class="fas fa-arrows-alt-v"></i></a>
					<a class="trash" title="Clear"><i class="fas fa-times"></i></a>
				</div>
			</div>
		@endforeach
	</div>
	@include('profiles.edit._buttons')

{!! Form::close() !!}
