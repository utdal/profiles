<section id="publications" role="region" class="card" aria-labelledby="publications-heading">
    <h2 id="publications-heading"><i class="fa fa-book" aria-hidden="true"></i> Publications
        @if($editable)
        <a class="btn btn-primary btn-sm" href="{{ route('profiles.edit', [$profile->slug, 'publications']) }}" data-toggle="class" data-toggle-class="fa-spin" data-target="#publications .fa-sync">
            @if($profile->hasOrcidManagedPublications())
                <i class="fas fa-sync"></i> Sync
            @else
                <i class="fas fa-edit" aria-label="Edit Publications"></i> Edit
            @endif
        </a>
        @endif
    </h2>
    <ul class="list-unstyled">
        @foreach($data as $pub)
            <li class="entry" aria-label="{{$pub->title}}">
                @if($pub->url)
                    <a target="_blank" href="{{$pub->url}}" class="has-external-link-icon">
                        <span class="has-external-link-icon">{!! Purify::clean($pub->title) !!}</span>
                        <i class="fas fa-external-link-alt" aria-hidden="true"></i>
                        <span class="sr-only"> (opens in a new tab)</span>
                    </a>
                    {{$pub->year}} - {{$pub->type}}
                @else
                    {!! Purify::clean($pub->title) !!} {{$pub->year}} - {{$pub->type}}
                @endif
            </li>
        @endforeach
    </ul>
    @if($paginated)
        {{ $data->links() }}
    @endif
</section>