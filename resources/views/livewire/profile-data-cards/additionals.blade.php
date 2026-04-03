<section id="additionals" class="card">
    <h2><i class="fas fa-sticky-note" aria-hidden="true"></i> Additional Information @if($editable)<a class="btn btn-primary btn-sm" href="{{ route('profiles.edit', [$profile->slug, 'additionals']) }}" aria-label="Edit Additional Information"><i class="fas fa-edit"></i> Edit</a>@endif</h2>
    <ul class="list-unstyled">
        @foreach($data as $additional)
            <li class="entry">
                <h3><i class="far fa-sticky-note" aria-hidden="true"></i> {{$additional->title}}</h3>
            </li>
        @endforeach
    </ul>
    @if($paginated)
        {{ $data->links() }}
    @endif
</section>