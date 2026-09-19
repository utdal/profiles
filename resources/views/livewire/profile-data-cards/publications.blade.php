<section id="publications" class="card">
    <h3><i class="fa fa-book" aria-hidden="true"></i> Publications
        @if($editable)
             @if($profile->hasOrcidManagedPublications())
                <a class="btn btn-primary btn-sm" href="{{ route('profiles.orcid', [$profile->slug, 'publications']) }}" data-toggle="class" data-toggle-class="fa-spin" data-target="#publications .fa-sync">
                    <i class="fas fa-sync"></i> Sync
                </a>
            @endif
            <a class="btn btn-primary btn-sm" href="{{ route('profiles.edit', [$profile->slug, 'publications']) }}">
                <i class="fas fa-edit" aria-label="Edit Publications"></i> Edit
            </a>
        @endif
    </h3>
    @foreach($data as $pub)
        <div class="entry">
            {!! Purify::clean($pub->title) !!} {{$pub->year}} - <strong>{{$pub->type}}</strong>
            @if($pub->url)
                <a target="_blank" href="{{$pub->url}}">
                    <span class="fas fa-external-link-alt" title="external link to publication"></span>
                </a>
            @endif
        </div>
    @endforeach
    @if($paginated)
        {{ $data->links() }}
    @endif
</section>