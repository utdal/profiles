@extends('profiles.edit.layout')

@section('section_name', 'Activities')

@section('form')
    @foreach ($data as $activity)
        <div class="row record form-group level lower-border" data-row-id="{{ $activity->id }}">
			@include('profiles.edit._actions')
            <div class="col col-lg-4 col-12">
                <input type="hidden" name="data[{{ $activity->id }}][id]" value="{{ $activity->id }}">
                <label for="data[{{ $activity->id }}][data][title]">Title</label>
                <textarea class="form-control" rows="4" id="data[{{ $activity->id }}][data][title]"
                    name="data[{{ $activity->id }}][data][title]">{{ $activity->title }}</textarea>
            </div>
            <div class="col col-lg-8 col-12">
                <label for="rte_{{ $activity->id }}">Description</label>
                <input id="data[{{ $activity->id }}][data][description]" type="hidden" class="clearable"
                    name="data[{{ $activity->id }}][data][description]" value="{{ $activity->description }}">
                <trix-editor input="data[{{ $activity->id }}][data][description]"></trix-editor>
            </div>
        </div>
    @endforeach
@endsection
