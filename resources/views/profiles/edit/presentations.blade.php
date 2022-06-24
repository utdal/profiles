@extends('profiles.edit.layout')

@section('section_name', 'Presentations')

@section('form')
    @foreach ($data as $pres)
        <div class="record lower-border" data-row-id="{{ $pres->id }}">
            @include('profiles.edit._actions')
            <div class="row form-group level">
                <div class="col col-md-6 col-12">
                    <input type="hidden" name="data[{{ $pres->id }}][id]" value="{{ $pres->id }}">
                    <label for="data[{{ $pres->id }}][data][title]">Title</label>
                    <textarea class="form-control" rows="4" id="data[{{ $pres->id }}][data][title]"
                        name="data[{{ $pres->id }}][data][title]">{{ $pres->title }}</textarea>
                </div>
                <div class="col col-md-6 col-12">
                    <label for="data[{{ $pres->id }}][data][description]">Description</label>
                    <input id="data[{{ $pres->id }}][data][description]" type="hidden" class="clearable"
                        name="data[{{ $pres->id }}][data][description]" value="{{ $pres->description }}">
                    <trix-editor input="data[{{ $pres->id }}][data][description]"></trix-editor>
                </div>
            </div>
            <div class="row form-group level">
                <div class="col col-md-4 col-12">
                    <label for="data[{{ $pres->id }}][data][url]">URL</label>
                    <input type="url" class="form-control" id="data[{{ $pres->id }}][data][url]"
                        name="data[{{ $pres->id }}][data][url]" value="{{ $pres->url }}">
                </div>
                <div class="col col-md-4 col-12">
                    <label for="data[{{ $pres->id }}][data][start_date]">Start Date</label>
                    <input type="text" class="datepicker month form-control"
                        id="data[{{ $pres->id }}][data][start_date]"
                        name="data[{{ $pres->id }}][data][start_date]" value="{{ $pres->start_date }}"
                        pattern="^[0-9]{4}\/[0-9]{2}$">
                </div>
                <div class="col col-md-4 col-12">
                    <label for="data[{{ $pres->id }}][data][end_date]">End Date</label>
                    <input type="text" class="datepicker month form-control"
                        id="data[{{ $pres->id }}][data][end_date]" name="data[{{ $pres->id }}][data][end_date]"
                        value="{{ $pres->end_date }}" pattern="^[0-9]{4}\/[0-9]{2}$">
                </div>
            </div>
        </div>
    @endforeach
@endsection
