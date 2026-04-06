<section id="awards" class="card">
    <h2><i class="fa fa-trophy" aria-hidden="true"></i> Awards @if($editable)<a class="btn btn-primary btn-sm" href="{{ route('profiles.edit', [$profile->slug, 'awards']) }}" aria-label="Edit Awards"><i class="fas fa-edit"></i> Edit</a>@endif</h2>
    <ul class="list-unstyled">
        @foreach($data as $award)
            <li class="entry">
                <strong>{{$award->name}}</strong> - <em>{{$award->organization}}</em> @if($award->year)[{{$award->year}}]@endif<br />
            </li>
        @endforeach
    </ul>
    @if($paginated)
        {{ $data->links() }}
    @endif
</section>