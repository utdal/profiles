<section id="areas" class="card">
    <h3><i class="fas fa-flask" aria-hidden="true"></i> Research Areas @if($editable)<a class="btn btn-primary btn-sm" href="{{ route('profiles.edit', [$profile->slug, 'areas']) }}" aria-label="Edit Research Areas"><i class="fas fa-edit"></i> Edit</a>@endif</h3>
    @foreach($data as $area)
        @if($area->url)
            <h5><a href="{{$area->url}}">{{$area->title}} <i class="fas fa-link" aria-hidden="true"></i></a></h5>
        @else
            <h5>{{$area->title}}</h5>
        @endif
        {!! Purify::clean($area->description) !!}
    @endforeach
    @if($paginated)
        {{ $data->links() }}
    @endif
</section>