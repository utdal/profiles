@extends('profiles.edit.layout')

@section('section_name', 'Publications')

@section('info')
    @include('profiles.edit._autosort_info')
@endsection

@section('form')
    @foreach ($data as $pub)
        <div class="record lower-border" data-row-id="{{ $pub->id }}">
            @include('profiles.edit._actions')
            <div class="row form-group level">
                <div class="col col-lg-7 col-12">
                    <input type="hidden" name="data[{{ $pub->id }}][id]" value="{{ $pub->id }}">
                    <input type="hidden" name="data[{{ $pub->id }}][doi]" value="{{ $pub->doi }}">
                    <label for="data[{{ $pub->id }}][data][title]">Title</label>
                    <input id="data[{{ $pub->id }}][data][title]" type="hidden" class="clearable"
                        name="data[{{ $pub->id }}][data][title]" value="{{ $pub->title }}">
                    <trix-editor input="data[{{ $pub->id }}][data][title]"></trix-editor>
                </div>
                <div class="col col-lg-3 col-12">
                    <label for="data[{{ $pub->id }}][data][doi]">DOI</label>
                    <input type="text" class="form-control" id="data[{{ $pub->id }}][data][doi]"
                        name="data[{{ $pub->id }}][data][doi]" value="{{ $pub->doi }}">
                </div>
                <div class="col col-lg-2 col-12">
                    <label for="data[{{ $pub->id }}][data][year]">Year</label>
                    <input type="text" class="datepicker year form-control" id="data[{{ $pub->id }}][data][year]"
                        name="data[{{ $pub->id }}][data][year]" value="{{ $pub->year }}" pattern="^[0-9]{4}$">
                </div>
            </div>
            <div class="row form-group level">
                <div class="col col-lg-4 col-12">
                    <label for="data[{{ $pub->id }}][data][url]">URL</label>
                    <input type="url" class="form-control" id="data[{{ $pub->id }}][data][url]"
                        name="data[{{ $pub->id }}][data][url]" value="{{ $pub->url }}">
                </div>
                <div class="col col-lg-4 col-12">
                    <label for="data[{{ $pub->id }}][data][group]">Group</label>
                    <input type="text" class="form-control" id="data[{{ $pub->id }}][data][group]"
                        name="data[{{ $pub->id }}][data][group]" value="{{ $pub->group }}">
                </div>
                <div class="col col-lg-2 col-12">
                    <label for="data[{{ $pub->id }}][data][type]">Type</label>
                    <input type="text" class="form-control" id="data[{{ $pub->id }}][data][type]"
                        name="data[{{ $pub->id }}][data][type]" value="{{ $pub->type }}">
                </div>
                <div class="col col-lg-2 col-12">
                    <label for="data[{{ $pub->id }}][data][status]">Status</label>
                    <input type="text" class="form-control" id="data[{{ $pub->id }}][data][status]"
                        name="data[{{ $pub->id }}][data][status]" value="{{ $pub->status }}">
                </div>
            </div>
        </div>
    @endforeach
@endsection
