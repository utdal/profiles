@if(!$data->isEmpty() || $editable)
    <div class="card">
        <h3 id="appointments"><i class="fa fa-calendar" aria-hidden="true"></i> Appointments @if($editable)<a class="btn btn-primary btn-sm" href="{{ route('profiles.edit', [$profile->slug, 'appointments']) }}"><i class="fas fa-edit"></i> Edit</a>@endif</h3>
        @foreach($data as $appt)
            <div class="entry">
                <strong>{{$appt->appointment}}</strong>
                <br>
                <em>{{$appt->organization}}</em> [{{$appt->start_date}}@if($appt->end_date)&ndash;{{$appt->end_date}}@else<span>&ndash;Present</span>@endif]<br />
                @if($appt->description)
                    {!! Purify::clean($appt->description) !!}
                @endif
            </div>
        @endforeach
        {{ $data->links() }}
    </div>
@endif