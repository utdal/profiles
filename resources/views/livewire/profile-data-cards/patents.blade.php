<section id="patents" class="card">
    <h3><i class="fa fa-trophy" aria-hidden="true"></i> Patents @if($editable)<a class="btn btn-primary btn-sm" href="{{ route('profiles.edit', [$profile->slug, 'patents']) }}" aria-label="Edit Awards"><i class="fas fa-edit"></i> Edit</a>@endif</h3>
    @foreach($data as $patent)
        <div class="entry">
            <strong>{{$patent->title}}</strong> - <em>{{ $patent->patentCitation }}.</em><br />
        </div>
    @endforeach
    @if($paginated)
        {{ $data->links() }}
    @endif
</section>