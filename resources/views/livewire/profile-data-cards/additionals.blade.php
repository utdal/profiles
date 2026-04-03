<section id="additionals" class="card">
    <h2><i class="fas fa-sticky-note" aria-hidden="true"></i> Additional Information @if($editable)<a class="btn btn-primary btn-sm" href="{{ route('profiles.edit', [$profile->slug, 'additionals']) }}" aria-label="Edit Additional Information"><i class="fas fa-edit"></i> Edit</a>@endif</h2>
    <ul class="list-unstyled">
        @foreach($data as $additional)
            <li class="entry">
                @if($additional->url)
                    <h3>
                        <a target="_blank" href="{{$additional->url}}" class="has-external-link-icon">
                            <i class="far fa-sticky-note" aria-hidden="true"></i>
                            <span class="has-external-link-icon">{!! Purify::clean($additional->title) !!}</span>
                            <i class="fas fa-external-link-alt"></i>
                            <span class="sr-only"> (opens in a new tab)</span>
                        </a>
                    </h3>
                @else
                    <h3><i class="far fa-sticky-note" aria-hidden="true"></i> {{$additional->title}}</h3>
                @endif
                {!! Purify::clean($additional->description) !!}
            </li>
        @endforeach
    </ul>
    @if($paginated)
        {{ $data->links() }}
    @endif
</section>