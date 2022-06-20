@extends('profiles.edit.layout')

@section('section_name', 'Research Areas')

@section('form')
    @foreach ($data as $area)
        <div class="row record form-group level lower-border" data-row-id="{{ $area->id }}">
            @include('profiles.edit._actions')
            <div class="col col-lg-3 col-12">
                <input type="hidden" name="data[{{ $area->id }}][id]" value="{{ $area->id }}">
                <label for="data[{{ $area->id }}][data][title]">Title</label>
                <textarea class="form-control" rows="4" id="data[{{ $area->id }}][data][title]"
                    name="data[{{ $area->id }}][data][title]">{{ $area->title }}</textarea>
            </div>
            <div class="col col-lg-8 col-12">
                <label for="data[{{ $area->id }}][data][description]">Description</label>
                <input id="data[{{ $area->id }}][data][description]" type="hidden" class="clearable"
                    name="data[{{ $area->id }}][data][description]" value="{{ $area->description }}">
                <trix-editor input="data[{{ $area->id }}][data][description]"></trix-editor>
            </div>
        </div>
    @endforeach
@endsection
