@if(!$data->isEmpty() || $editable)
    <div class="card">
        <h3 id="additional"><i class="fas fa-sticky-note" aria-hidden="true"></i> Additional Information @if($editable)<a class="btn btn-primary btn-sm" href="{{ route('profiles.edit', [$profile->slug, 'additionals']) }}"><i class="fas fa-edit"></i> Edit</a>@endif</h3>
        @foreach($data as $additional)
            <div class="entry">
                <h5><i class="far fa-sticky-note" aria-hidden="true"></i> {{$additional->title}}</h5>
                    {!! Purify::clean($additional->description) !!}
            </div>
        @endforeach
        {{ $data->links() }}
    </div>
@endif