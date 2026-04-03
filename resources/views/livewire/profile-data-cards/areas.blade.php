<section id="areas" class="card">
    <h2><i class="fas fa-flask" aria-hidden="true"></i> Research Areas @if($editable)<a class="btn btn-primary btn-sm" href="{{ route('profiles.edit', [$profile->slug, 'areas']) }}" aria-label="Edit Research Areas"><i class="fas fa-edit"></i> Edit</a>@endif</h2>
    @foreach($data as $area)
        @if($area->url)
            <h3><a href="{{$area->url}}">{{$area->title}} <i class="fas fa-link" aria-hidden="true"></i></a></h3>
        @else
            <h3>{{$area->title}}</h3>
        @endif
        {!! Purify::clean($area->description) !!}
    @endforeach
    @if($paginated)
        {{ $data->links() }}
    @endif
</section>