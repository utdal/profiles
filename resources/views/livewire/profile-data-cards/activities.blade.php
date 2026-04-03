<section id="activities" role="region" class="card" aria-labelledby="activities-heading">
    <h2 id="activities-heading"><i class="fas fa-chart-line" aria-hidden="true"></i> Activities @if($editable)<a class="btn btn-primary btn-sm" href="{{ route('profiles.edit', [$profile->slug, 'activities']) }}" aria-label="Edit Publications"><i class="fas fa-edit"></i> Edit</a>@endif</h2>
    <ul class="list-unstyled">
        @foreach($data as $activity)
            <li aria-label="{{$activity->title}}">
                <article>
                    @if($activity->title)
                        <h3>{{$activity->title}}</h3>
                    @endif
                    <p>{!! Purify::clean($activity->description) !!}</p>
                    @if($activity->start_date)[{{$activity->start_date}}&ndash;{{$activity->end_date}}] @endif
                </article>
            </li>
        @endforeach
    </ul>
    @if($paginated)
        {{ $data->links() }}
    @endif
</section>