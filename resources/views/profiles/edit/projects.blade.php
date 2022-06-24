@extends('profiles.edit.layout')

@section('section_name', 'Projects')

@section('form')
    @foreach ($data as $proj)
        <div class="record lower-border" data-row-id="{{ $proj->id }}">
            @include('profiles.edit._actions')
            <div class="row form-group level">
                <div class="col col-md-6 col-12">
                    <input type="hidden" name="data[{{ $proj->id }}][id]" value="{{ $proj->id }}">
                    <label for="data[{{ $proj->id }}][data][title]">Title</label>
                    <textarea class="form-control" rows="4" id="data[{{ $proj->id }}][data][title]"
                        name="data[{{ $proj->id }}][data][title]">{{ $proj->title }}</textarea>
                </div>
                <div class="col col-md-6 col-12">
                    <label for="data[{{ $proj->id }}][data][description]">Description</label>
                    <input id="data[{{ $proj->id }}][data][description]" type="hidden" class="clearable"
                        name="data[{{ $proj->id }}][data][description]" value="{{ $proj->description }}">
                    <trix-editor input="data[{{ $proj->id }}][data][description]"></trix-editor>
                </div>
            </div>
            <div class="row form-group level">
                <div class="col col-md-4 col-12">
                    <label for="data[{{ $proj->id }}][data][url]">URL</label>
                    <input type="url" class="form-control" id="data[{{ $proj->id }}][data][url]"
                        name="data[{{ $proj->id }}][data][url]" value="{{ $proj->url }}">
                </div>
                <div class="col col-md-4 col-12">
                    <label for="data[{{ $proj->id }}][data][start_date]">Start Date</label>
                    <input type="text" class="datepicker month form-control"
                        id="data[{{ $proj->id }}][data][start_date]"
                        name="data[{{ $proj->id }}][data][start_date]" value="{{ $proj->start_date }}"
                        pattern="^[0-9]{4}\/[0-9]{2}$">
                </div>
                <div class="col col-md-4 col-12">
                    <label for="data[{{ $proj->id }}][data][end_date]">End Date</label>
                    <input type="text" class="datepicker month form-control"
                        id="data[{{ $proj->id }}][data][end_date]" name="data[{{ $proj->id }}][data][end_date]"
                        value="{{ $proj->end_date }}" pattern="^[0-9]{4}\/[0-9]{2}$">
                </div>
            </div>
        </div>
    @endforeach
@endsection
