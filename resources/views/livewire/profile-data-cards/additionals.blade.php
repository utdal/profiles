<section id="additionals" class="card">
    <h2><i class="fas fa-sticky-note" aria-hidden="true"></i> Additional Information @if($editable)<a class="btn btn-primary btn-sm" href="{{ route('profiles.edit', [$profile->slug, 'additionals']) }}" aria-label="Edit Additional Information"><i class="fas fa-edit"></i> Edit</a>@endif</h2>
    @foreach($data as $additional)
        <div class="entry">
            <h3><i class="far fa-sticky-note" aria-hidden="true"></i> {{$additional->title}}</h3>
                {!! Purify::clean($additional->description) !!}
        </div>
    @endforeach
    @if($paginated)
        {{ $data->links() }}
    @endif
</section>