<section id="affiliations" class="card" aria-labelledby="affiliations-heading">
    <h2 id="affiliations-heading"><i class="fas fa-users" aria-hidden="true"></i> Affiliations @if($editable)<a class="btn btn-primary btn-sm" href="{{ route('profiles.edit', [$profile->slug, 'affiliations']) }}" aria-label="Edit Affiliations"><i class="fas fa-edit"></i> Edit</a>@endif</h2>
    <ul class="list-unstyled">
        @foreach($data as $affiliation)
            <li class="entry">
                <h3>{{$affiliation->title}}</h3>
                @if($affiliation->start_date)<strong>{{$affiliation->start_date}}@if($affiliation->end_date)&ndash;{{$affiliation->end_date}}@endif</strong><br>@endif
                <div>{!! Purify::clean($affiliation->description) !!}</div>
            </li>
        @endforeach
    </ul>
    @if($paginated)
        {{ $data->links() }}
    @endif
</section>