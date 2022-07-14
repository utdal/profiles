@if(!$data->isEmpty() || $editable)
    <div class="card">
        <h3 id="news"><i class="fas fa-newspaper" aria-hidden="true"></i> News Articles @if($editable)<a class="btn btn-primary btn-sm" href="{{ route('profiles.edit', [$profile->slug, 'news']) }}"><i class="fas fa-edit"></i> Edit</a>@endif</h3>
        @foreach($data as $article)
            <div class="entry">
                @if($article->url)
                    <h5>
                        <a href="{{$article->url}}" target="_blank" title="link to article">
                            {{$article->title}} <i class="fas fa-external-link-alt" aria-hidden="true"></i>
                        </a>
                    </h5>
                @else
                    <h5>{{$article->title}}</h5>
                @endif
                @if($article->image)<img src="{{ $article->imageUrl }}" class="news_image" alt="{{ $article->image_alt ?? $article->title }}"/>@endif
                {!! Purify::clean($article->description) !!}
            </div>
        @endforeach
        {{ $data->links() }}
    </div>
@endif