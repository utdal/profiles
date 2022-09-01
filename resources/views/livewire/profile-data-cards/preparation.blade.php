<section id="preparation" class="card">
    <h3><i class="fas fa-graduation-cap" aria-hidden="true"></i> Professional Preparation @if($editable)<a class="btn btn-primary btn-sm" href="{{ route('profiles.edit', [$profile->slug, 'preparation']) }}"><i class="fas fa-edit"></i> Edit</a>@endif</h3>
    @foreach($data as $prep)
        <div class="entry">
            {{$prep->degree}} @if($prep->major)- {{$prep->major}}@endif
            <br>
            <strong>{{$prep->institution}}</strong>@if($prep->year) - {{$prep->year}}@endif
        </div>
    @endforeach
    @if($paginated)
        {{ $data->links() }}
    @endif
</section>