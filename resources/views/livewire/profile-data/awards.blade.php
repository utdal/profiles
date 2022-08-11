@if(!$data->isEmpty() || $editable)
    <div class="card">
        <h3 id="awards"><i class="fa fa-trophy" aria-hidden="true"></i> Awards @if($editable)<a class="btn btn-primary btn-sm" href="{{ route('profiles.edit', [$profile->slug, 'awards']) }}"><i class="fas fa-edit"></i> Edit</a>@endif</h3>
        @foreach($data as $award)
            <div class="entry">
                <strong>{{$award->name}}</strong> - <em>{{$award->organization}}</em> @if($award->year)[{{$award->year}}]@endif<br />
            </div>
        @endforeach
        @if($paginated)
            {{ $data->links() }}
        @endif
    </div>
@endif