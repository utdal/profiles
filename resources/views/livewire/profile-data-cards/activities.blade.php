<section id="activities" class="card">
    <h3><i class="fas fa-chart-line" aria-hidden="true"></i> Activities @if($editable)<a class="btn btn-primary btn-sm" href="{{ route('profiles.edit', [$profile->slug, 'activities']) }}"><i class="fas fa-edit"></i> Edit</a>@endif</h3>
    @foreach($data as $activity)
        <div class="entry">
            <h5>{{$activity->title}}</h5>
            {!! Purify::clean($activity->description) !!}
            @if($activity->start_date)[{{$activity->start_date}}&ndash;{{$activity->end_date}}] @endif
        </div>
    @endforeach
    @if($paginated)
        {{ $data->links() }}
    @endif
</section>