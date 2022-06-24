@extends('profiles.edit.layout')

@section('section_name', 'Professional Preparation')

@section('info')
    @include('profiles.edit._autosort_info')
@endsection

@section('form')
    @foreach ($data as $prep)
        <div class="row record form-group lower-border" data-row-id="{{ $prep->id }}">
            @include('profiles.edit._actions')
            <div class="col col-lg-3 col-sm-6 col-12">
                <input type="hidden" name="data[{{ $prep->id }}][id]" value="{{ $prep->id }}">
                <label for="data[{{ $prep->id }}][data][degree]">Degree</label>
                <input type="text" class="form-control" id="data[{{ $prep->id }}][data][degree]"
                    name="data[{{ $prep->id }}][data][degree]" value="{{ $prep->degree }}">
            </div>
            <div class="col col-lg-3 col-sm-6 col-12">
                <label for="data[{{ $prep->id }}][data][major]">Major</label>
                <input type="text" class="form-control" id="data[{{ $prep->id }}][data][major]"
                    name="data[{{ $prep->id }}][data][major]" value="{{ $prep->major }}">
            </div>
            <div class="col col-lg-3 col-sm-6 col-12">
                <label for="data[{{ $prep->id }}][data][institution]">Institution</label>
                <input type="text" class="form-control" id="data[{{ $prep->id }}][data][institution]"
                    name="data[{{ $prep->id }}][data][institution]" value="{{ $prep->institution }}">
            </div>
            <div class="col col-lg-3 col-sm-6 col-12">
                <label for="data[{{ $prep->id }}][data][year]">Year</label>
                <input type="text" class="datepicker year form-control" id="data[{{ $prep->id }}][data][year]"
                    name="data[{{ $prep->id }}][data][year]" value="{{ $prep->year }}" pattern="^[0-9]{4}$">
            </div>
        </div>
    @endforeach
@endsection
