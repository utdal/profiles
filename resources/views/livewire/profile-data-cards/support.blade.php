<section id="funding" class="card">
    <h3><i class="fas fa-dollar-sign" aria-hidden="true"></i> Funding @if($editable)<a class="btn btn-primary btn-sm" href="{{ route('profiles.edit', [$profile->slug, 'support']) }}" aria-label="Edit Funding"><i class="fas fa-edit"></i> Edit</a>@endif</h3>
    @foreach($data as $funding)
        <div class="entry">
            @if($funding->url)
                <h5><a href="{{$funding->url}}">{{$funding->title}} <i class="fas fa-link" aria-hidden="true"></i></a></h5>
            @else
                <h5>{{$funding->title}}</h5>
            @endif
            <h6>{{$funding->amount}} - {{$funding->sponsor}} [{{$funding->start_date}}@if($funding->end_date)&ndash;{{$funding->end_date}}@endif]</h6>
            {{ $funding->description }}
        </div>
    @endforeach
    @if($paginated)
        {{ $data->links() }}
    @endif
</section>