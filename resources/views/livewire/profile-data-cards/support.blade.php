<section id="funding" class="card">
    <h2><i class="fas fa-dollar-sign" aria-hidden="true"></i> Funding @if($editable)<a class="btn btn-primary btn-sm" href="{{ route('profiles.edit', [$profile->slug, 'support']) }}" aria-label="Edit Funding"><i class="fas fa-edit"></i> Edit</a>@endif</h2>
    <ul class="list-unstyled">
        @foreach($data as $funding)
            <li class="entry">
                @if($funding->url)
                    <h3>
                        <a href="{{$funding->url}}" target="_blank" class="has-external-link-icon">
                            <span class="has-external-link-icon">{{$funding->title}}</span>
                            <i class="fas fa-external-link-alt" aria-hidden="true"></i>
                            <span class="sr-only"> (opens in a new tab)</span>
                        </a>
                    </h3>
                @else
                    <h3>{{$funding->title}}</h3>
                @endif
                <h4>{{$funding->amount}} - {{$funding->sponsor}} [{{$funding->start_date}}@if($funding->end_date)&ndash;{{$funding->end_date}}@endif]</h4>
                {{ $funding->description }}
            </li>
        @endforeach
    </ul>
    @if($paginated)
        {{ $data->links() }}
    @endif
</section>