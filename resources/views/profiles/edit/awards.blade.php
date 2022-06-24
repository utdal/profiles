@extends('profiles.edit.layout')

@section('section_name', 'Awards')

@section('info')
    @include('profiles.edit._autosort_info')
@endsection

@section('form')
    @foreach ($data as $award)
        <div class="row record form-group lower-border" data-row-id="{{ $award->id }}">
            @include('profiles.edit._actions')
            <div class="col col-md-4 col-12">
                <input type="hidden" name="data[{{ $award->id }}][id]" value="{{ $award->id }}">
                <label for="data[{{ $award->id }}][data][name]">Name</label>
                <input type="text" class="form-control" id="data[{{ $award->id }}][data][name]"
                    name="data[{{ $award->id }}][data][name]" value="{{ $award->name }}">
            </div>
            <div class="col col-md-3 col-12">
                <label for="data[{{ $award->id }}][data][organization]">Organization</label>
                <input type="text" class="form-control" id="data[{{ $award->id }}][data][organization]"
                    name="data[{{ $award->id }}][data][organization]" value="{{ $award->organization }}">
            </div>
            <div class="col col-md-2 col-12">
                <label for="data[{{ $award->id }}][data][year]">Year</label>
                <input type="text" class="datepicker year form-control" id="data[{{ $award->id }}][data][year]"
                    name="data[{{ $award->id }}][data][year]" value="{{ $award->year }}" pattern="^[0-9]{4}$">
            </div>
            <div class="col-md-3 col-xs-12">
                <label for="data[{{ $award->id }}][data][category]">Category</label>
                <select class="form-control" id="data[{{ $award->id }}][data][category]"
                    name="data[{{ $award->id }}][data][category]">
                    <option disabled selected value> -- Select a Category -- </option>
                    <option value="Teaching" @if ($award->category == 'Teaching') selected @endif>Teaching</option>
                    <option value="Research" @if ($award->category == 'Research') selected @endif>Research</option>
                    <option value="Service" @if ($award->category == 'Service') selected @endif>Service</option>
                    <option value="Additional" @if ($award->category == 'Additional') selected @endif>Additional</option>
                </select>
            </div>
        </div>
    @endforeach
@endsection
