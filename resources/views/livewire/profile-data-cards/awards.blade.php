<section id="awards" class="card">
    <h3><i class="fa fa-trophy" aria-hidden="true"></i> Awards @if($editable)<a class="btn btn-primary btn-sm" href="{{ route('profiles.edit', [$profile->slug, 'awards']) }}" aria-label="Edit Awards"><i class="fas fa-edit"></i> Edit</a>@endif</h3>
    @foreach($data as $award)
        <div class="entry">
            <strong>{{$award->name}}</strong> - <em>{{$award->organization}}</em> @if($award->year)[{{$award->year}}]@endif<br />
        </div>
    @endforeach
    @if($paginated)
        {{ $data->links() }}
    @endif
</section>