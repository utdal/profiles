@if(!$data->isEmpty() || $editable)
    <div class="card">
        <h3 id="publications"><i class="fa fa-book" aria-hidden="true"></i> Publications
            @if($editable)
            <a class="btn btn-primary btn-sm" href="{{ route('profiles.edit', [$profile->slug, 'publications']) }}" data-toggle="class" data-toggle-class="fa-spin" data-target="#publications .fa-sync">
                @if($profile->hasOrcidManagedPublications())
                    <i class="fas fa-sync"></i> Sync
                @else
                    <i class="fas fa-edit"></i> Edit
                @endif
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
        {{ $data->links() }}
    </div>
@endif