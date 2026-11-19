<ul>
    @foreach($tags as $tag)
        <li><a class="badge badge-primary tags-badge" href="{{ route('tags.show', ['slug' => $tag->slug]) }}">{{ $tag->name }}</a></li>
    @endforeach
</ul>