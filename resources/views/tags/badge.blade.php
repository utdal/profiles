<ul>
    @foreach($tags as $tag)
        <li><a class="badge badge-primary tags-badge" href="{{ route('profiles.index', ['search' => $tag->name]) }}">{{ $tag->name }}</a></li>
    @endforeach
</ul>