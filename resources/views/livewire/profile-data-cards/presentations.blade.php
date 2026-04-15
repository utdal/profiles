<section id="presentations" role="region" class="card" aria-labelledby="presentations-heading">
    <h2 id="presentations-heading"><i class="fas fa-laptop" aria-hidden="true"></i> Presentations @if($editable)<a class="btn btn-primary btn-sm" href="{{ route('profiles.edit', [$profile->slug, 'presentations']) }}" aria-label="Edit Presentations"><i class="fas fa-edit"></i> Edit</a>@endif</h2>
    <ul class="list-unstyled">
        @foreach($data as $presentation)
            <li class="entry">
                <article>
                    @if($presentation->url)
                    <h3>
                        <a href="{{$presentation->url}}" target="_blank" class="has-external-link-icon">
                            <span class="has-external-link-icon">{{$presentation->title}}</span>
                            <i class="fas fa-external-link-alt" aria-hidden="true"></i>
                            <span class="sr-only"> (opens in a new tab)</span>
                        </a>
                    </h3>
                    @else
                        <h3>{{$presentation->title}}</h3>
                    @endif
                    @if($presentation->start_date)
                        <strong>{{$presentation->start_date}}
                            @if($presentation->end_date)
                                &ndash;{{$presentation->end_date}}
                            @endif
                        </strong>
                    @endif
                    @if($presentation->description)
                        <em>{!! Purify::clean($presentation->description) !!}</em>
                    @endif
                </article>
            </li>
        @endforeach
    </ul>
    @if($paginated)
        {{ $data->links() }}
    @endif
</section>