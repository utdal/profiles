<section id="areas" role="region" class="card" aria-labelledby="areas-heading">
    <h2 id="areas-heading"><i class="fas fa-flask" aria-hidden="true"></i> Research Areas @if($editable)<a class="btn btn-primary btn-sm" href="{{ route('profiles.edit', [$profile->slug, 'areas']) }}" aria-label="Edit Research Areas"><i class="fas fa-edit"></i> Edit</a>@endif</h2>
    <ul class="list-unstyled">
        @foreach($data as $area)
            <li>
                @if($area->url)
                    <h3>
                        <a href="{{$area->url}}" target="_blank" class="has-external-link-icon">
                            <span class="has-external-link-icon">{{$area->title}}</span>
                            <i class="fas fa-external-link-alt" aria-hidden="true"></i>
                            <span class="sr-only"> (opens in a new tab)</span>
                        </a>
                    </h3>
                @else
                    <h3>{{$area->title}}</h3>
                @endif
                <p>{!! Purify::clean($area->description) !!}</p>
            </li>
        @endforeach
    </ul>
    @if($paginated)
        {{ $data->links() }}
    @endif
</section>