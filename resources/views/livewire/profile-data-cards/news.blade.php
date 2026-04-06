<section id="news" class="card">
    <h2><i class="fas fa-newspaper" aria-hidden="true"></i> News Articles @if($editable)<a class="btn btn-primary btn-sm" href="{{ route('profiles.edit', [$profile->slug, 'news']) }}" aria-label="Edit News Articles"><i class="fas fa-edit"></i> Edit</a>@endif</h2>
    <ul class="list-unstyled">
        @foreach($data as $article)
            <li class="entry">
                <article>
                    @if($article->url)
                        <h3>
                            <a href="{{$article->url}}" target="_blank" class="has-external-link-icon">
                                <span class="has-external-link-icon">{{$article->title}}</span>
                                <i class="fas fa-external-link-alt" aria-hidden="true"></i>
                                <span class="sr-only"> (opens in a new tab)</span>
                            </a>
                        </h3>
                    @else
                        <h3>{{$article->title}}</h3>
                    @endif
                    @if($article->image)
                        <img src="{{ $article->imageUrl }}" class="news_image" alt="{{ $article->image_alt ?? $article->title }}"/>
                    @endif
                    {!! Purify::clean($article->description) !!}
                </article>
            </li>
        @endforeach
    </ul>
    @if($paginated)
        {{ $data->links() }}
    @endif
</section>