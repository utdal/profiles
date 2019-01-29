@foreach($tags as $tag)
<a class="badge badge-primary tags-badge" href="{{ route('profiles.index', ['search' => $tag->name]) }}">{{ $tag->name }}</a>
@endforeach