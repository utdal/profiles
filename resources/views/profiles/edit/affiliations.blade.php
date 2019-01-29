<h1>Edit <a href="{{ action('ProfilesController@show', [$profile->slug]) }}">{{$profile->name}}</a>'s Affiliations</h1>

{!! Form::open(['url' => route('profiles.update', [$profile->slug, 'affiliations'])]) !!}
	<div class="sortable">
		@foreach($data as $affil)
			<div class="row record form-group level lower-border">
				<div class="col col-lg-3 col-12">
					<input type="hidden" name="data[{{$affil->id}}][id]"  value="{{$affil->id}}" />
					<label for="data[{{$affil->id}}][data][title]">Title</label>
					<textarea class="form-control" rows="4" id="data[{{$affil->id}}][data][title]" name="data[{{$affil->id}}][data][title]">{{$affil->title}}</textarea>
				</div>
				<div class="col col-lg-5 col-12">
					<label for="rte_{{$affil->id}}">Description</label>
					<input id="rte_{{$affil->id}}" type="hidden" class="clearable" name="data[{{$affil->id}}][data][description]" value="{{$affil->description}}">
					<trix-editor input="rte_{{$affil->id}}"></trix-editor>
				</div>
				<div class="col col-lg-2 col-12">
					<label for="data[{{$affil->id}}][data][start_date]">Start Date</label>
					<input type="text" class="datepicker month form-control" id="data[{{$affil->id}}][data][start_date]" name="data[{{$affil->id}}][data][start_date]" value="{{$affil->start_date}}" pattern="^[0-9]{4}\/[0-9]{2}$"/>
				</div>
				<div class="col col-lg-2 col-12">
					<label for="data[{{$affil->id}}][data][end_date]">End Date</label>
					<input type="text" class="datepicker month form-control" id="data[{{$affil->id}}][data][end_date]" name="data[{{$affil->id}}][data][end_date]" value="{{$affil->end_date}}" pattern="^[0-9]{4}\/[0-9]{2}$"/>
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
