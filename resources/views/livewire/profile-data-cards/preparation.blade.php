<section id="preparation" role="region" class="card" aria-labelledby="preparation-heading">
    <h2 id="preparation-heading"><i class="fas fa-graduation-cap" aria-hidden="true"></i> Professional Preparation @if($editable)<a class="btn btn-primary btn-sm" href="{{ route('profiles.edit', [$profile->slug, 'preparation']) }}" aria-label="Edit Professional Preparation"><i class="fas fa-edit"></i> Edit</a>@endif</h2>
    <ul class="list-unstyled">
        @foreach($data as $prep)
            <li class="entry">
                <p>
                    {{$prep->degree}} @if($prep->major)- {{$prep->major}}@endif
                    <br>
                    <strong>{{$prep->institution}}</strong>@if($prep->year) - {{$prep->year}}@endif
                </p>
            </li>
        @endforeach
    </ul>
    @if($paginated)
        {{ $data->links() }}
    @endif
</section>