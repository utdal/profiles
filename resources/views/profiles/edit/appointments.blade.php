@extends('profiles.edit.layout')

@section('section_name', 'Appointments')

@section('form')
    @foreach ($data as $appoint)
        <div class="record lower-border" data-row-id="{{ $appoint->id }}">
            @include('profiles.edit._actions')
            <div class="row form-group level">
                <div class="col col-lg-6 col-12">
                    <input type="hidden" name="data[{{ $appoint->id }}][id]" value="{{ $appoint->id }}">
                    <label for="data[{{ $appoint->id }}][data][appointment]">Appointment</label>
                    <input type="text" class="form-control" id="data[{{ $appoint->id }}][data][appointment]"
                        name="data[{{ $appoint->id }}][data][appointment]" value="{{ $appoint->appointment }}">
                </div>
                <div class="col col-lg-6 col-12">
                    <label for="data[{{ $appoint->id }}][data][organization]">Organization</label>
                    <input type="text" class="form-control" id="data[{{ $appoint->id }}][data][organization]"
                        name="data[{{ $appoint->id }}][data][organization]" value="{{ $appoint->organization }}">
                </div>
            </div>
            <div class="row form-group level">
                <div class="col col-lg-6 col-12">
                    <label for="data[{{ $appoint->id }}][data][description]">Description</label>
                    <textarea class="form-control" rows="4" id="data[{{ $appoint->id }}][data][description]"
                        name="data[{{ $appoint->id }}][data][description]">{{ $appoint->description }}</textarea>
                </div>
                <div class="col col-lg-3 col-12">
                    <label for="data[{{ $appoint->id }}][data][start_date]">Start Date</label>
                    <input type="text" class="datepicker year form-control"
                        id="data[{{ $appoint->id }}][data][start_date]"
                        name="data[{{ $appoint->id }}][data][start_date]" value="{{ $appoint->start_date }}"
                        pattern="^[0-9]{4}$">
                </div>
                <div class="col col-lg-3 col-12">
                    <label for="data[{{ $appoint->id }}][data][end_date]">End Date</label>
                    <input type="text" class="datepicker year form-control"
                        id="data[{{ $appoint->id }}][data][end_date]" name="data[{{ $appoint->id }}][data][end_date]"
                        value="{{ $appoint->end_date }}" pattern="^[0-9]{4}$">
                </div>
            </div>
        </div>
    @endforeach
@endsection
