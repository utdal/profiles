@extends('profiles.edit.layout')

@section('section_name', 'Additional Information')

@section('form')
    @foreach ($data as $add)
        <div class="row record form-group level lower-border" data-row-id="{{ $add->id }}">
            @include('profiles.edit._actions')
            <div class="col col-lg-3 col-12">
                <input type="hidden" name="data[{{ $add->id }}][id]" value="{{ $add->id }}">
                <label for="data[{{ $add->id }}][data][title]">Title</label>
                <textarea class="form-control" rows="4" id="data[{{ $add->id }}][data][title]"
                    name="data[{{ $add->id }}][data][title]">{{ $add->title }}</textarea>
            </div>
            <div class="col col-lg-7 col-12">
                <label for="data[{{ $add->id }}][data][description]">Description</label>
                <input id="data[{{ $add->id }}][data][description]" type="hidden" class="clearable"
                    name="data[{{ $add->id }}][data][description]" value="{{ $add->description }}">
                <trix-editor input="data[{{ $add->id }}][data][description]"></trix-editor>
            </div>
            <div class="col col-lg-2 col-12">
                <label for="data[{{ $add->id }}][data][url]">URL</label>
                <input type="url" class="form-control" id="data[{{ $add->id }}][data][url]"
                    name="data[{{ $add->id }}][data][url]" value="{{ $add->url }}">
            </div>
        </div>
    @endforeach
@endsection
