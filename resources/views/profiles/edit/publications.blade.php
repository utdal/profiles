<h1>Edit <a href="{{ action('ProfilesController@show', [$profile->slug]) }}">{{$profile->name}}</a>'s Publications</h1>

{!! Form::open(['url' => route('profiles.update', [$profile->slug, 'publications'])]) !!}
		@foreach($data as $pub)
			<div class="record lower-border">
				<div class="actions">
					<a class="trash" title="Clear"><i class="fas fa-times"></i></a>
				</div>
				<div class="row form-group level">
					<div class="col col-lg-9 col-12">
						<input type="hidden" name="data[{{$pub->id}}][id]"  value="{{$pub->id}}" />
						<label for="data[{{$pub->id}}][data][title]">Title</label>
						<input id="rte_{{$pub->id}}" type="hidden" class="clearable" id="data[{{$pub->id}}][data][title]" name="data[{{$pub->id}}][data][title]" value="{{$pub->title}}" />
						<trix-editor input="rte_{{$pub->id}}"></trix-editor>
					</div>
					<div class="col col-lg-3 col-12">
						<label for="data[{{$pub->id}}][data][year]">Year</label>
						<input type="text" class="datepicker year form-control" id="data[{{$pub->id}}][data][year]" name="data[{{$pub->id}}][data][year]" value="{{$pub->year}}" pattern="^[0-9]{4}$"/>
					</div>
				</div>
				<div class="row form-group level">
					<div class="col col-lg-4 col-12">
						<label for="data[{{$pub->id}}][data][url]">URL</label>
						<input type="url" class="form-control" id="data[{{$pub->id}}][data][url]" name="data[{{$pub->id}}][data][url]" value="{{$pub->url}}" />
					</div>
					<div class="col col-lg-4 col-12">
						<label for="data[{{$pub->id}}][data][group]">Group</label>
						<input type="text" class="form-control" id="data[{{$pub->id}}][data][group]" name="data[{{$pub->id}}][data][group]" value="{{$pub->group}}" />
					</div>
					<div class="col col-lg-2 col-12">
						<label for="data[{{$pub->id}}][data][type]">Type</label>
						<input type="text" class="form-control" id="data[{{$pub->id}}][data][type]" name="data[{{$pub->id}}][data][type]" value="{{$pub->type}}" />
					</div>
					<div class="col col-lg-2 col-12">
						<label for="data[{{$pub->id}}][data][status]">Status</label>
						<input type="text" class="form-control" id="data[{{$pub->id}}][data][status]" name="data[{{$pub->id}}][data][status]" value="{{$pub->status}}" />
					</div>
				</div>
			</div>
		@endforeach
		
		@include('profiles.edit._buttons')

	</div>


{!! Form::close() !!}
