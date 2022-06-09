<h1>Edit <a href="{{ action('ProfilesController@show', [$profile->slug]) }}">{{$profile->name}}</a>'s News</h1>

{!! Form::open(['url' => route('profiles.update', [$profile->slug, 'news']), 'files' => true]) !!}
	<div class="sortable">
		@foreach($data as $news)
			<div class="row record form-group level lower-border">
				<div class="col col-lg-6 col-12">
					<input type="hidden" name="data[{{$news->id}}][id]"  value="{{$news->id}}" />
					<label for="data[{{$news->id}}][data][title]">Title</label>
					<input class="form-control" id="data[{{$news->id}}][data][title]" name="data[{{$news->id}}][data][title]" value="{{$news->title}}"/>
				</div>
				<div class="col col-lg-6 col-12">
					<label for="data[{{$news->id}}][data][url]">URL</label>
					<input type="url" class="form-control" id="data[{{$news->id}}][data][url]" name="data[{{$news->id}}][data][url]" value="{{$news->url}}" />
				</div>
				<div class="col col-lg-12 col-12">
					<label for="rte_{{$news->id}}">Description</label>
					<input id="rte_{{$news->id}}" type="hidden" class="clearable" name="data[{{$news->id}}][data][description]" value="{{$news->description}}">
					<trix-editor aria-label="Description" input="rte_{{$news->id}}"></trix-editor>
				</div>
				<div class="col col-lg-4 col-12">
					<label for="data[{{$news->id}}][image]-img">Image</label>
					<img class="profile_photo d-flex" id="data[{{$news->id}}][image]-img" src="@if($news->imageUrl != asset('/img/default.png')){{$news->imageUrl}}@endif">
					<div class="custom-file form-control">
						<input type="file" id="data[{{$news->id}}][image]" name="data[{{$news->id}}][image]" accept="image/*" class="custom-file-input clickable">
						<label id="label-{{$news->id}}" for="data[{{$news->id}}][image]" class="custom-file-label">
							{{ $news->image->file_name ?? 'Select an image' }}
						</label>
					</div>
					@foreach($errors->get("data.{$news->id}.image") as $image_error)
						@include('alert', ['message' => $image_error, 'type' => 'danger'])
						<p class="d-block invalid-feedback"><i class="fas fa-asterisk"></i> {!! $image_error !!}</p>
					@endforeach
				</div>
				<div class="col-lg-8 col col-12">
					<label for="data[{{$news->id}}][data][image_alt]">Image Description (Alt)</label>
					<input class="form-control" id="data[{{$news->id}}][data][image_alt]" name="data[{{$news->id}}][data][image_alt]" value="{{$news->image_alt}}" />
				</div>
				<div class="actions">
					<label for="data[{{$news->id}}][public]"></label>
					<label class="switch" title="Visibility">
						<input type="hidden" name="data[{{$news->id}}][public]" id="data[{{$news->id}}][public]" value="0">
						<input type="checkbox" name="data[{{$news->id}}][public]" id="data[{{$news->id}}][public]" value="1" @if($news->public) checked @endif>
						<span class="slider round"></span>
					</label>
					<a class="handle" title="Sort"><i class="fas fa-arrows-alt-v"></i></a>
					<a class="trash" title="Clear"><i class="fas fa-times"></i></a>
				</div>
			</div>
		@endforeach
	</div>

	@include('profiles.edit._buttons')

{!! Form::close() !!}
