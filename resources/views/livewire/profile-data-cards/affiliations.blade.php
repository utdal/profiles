<section id="affiliations" class="card">
    <h2><i class="fas fa-users" aria-hidden="true"></i> Affiliations @if($editable)<a class="btn btn-primary btn-sm" href="{{ route('profiles.edit', [$profile->slug, 'affiliations']) }}" aria-label="Edit Affiliations"><i class="fas fa-edit"></i> Edit</a>@endif</h2>
    @foreach($data as $affiliation)
        <div class="entry">
            <h3>{{$affiliation->title}}</h3>
            @if($affiliation->start_date)<strong>{{$affiliation->start_date}}@if($affiliation->end_date)&ndash;{{$affiliation->end_date}}@endif</strong><br>@endif
            {!! Purify::clean($affiliation->description) !!}
        </div>
    @endforeach
    @if($paginated)
        {{ $data->links() }}
    @endif
</section>