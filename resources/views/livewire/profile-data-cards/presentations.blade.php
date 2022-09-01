<section id="presentations" class="card">
    <h3><i class="fas fa-laptop" aria-hidden="true"></i> Presentations @if($editable)<a class="btn btn-primary btn-sm" href="{{ route('profiles.edit', [$profile->slug, 'presentations']) }}"><i class="fas fa-edit"></i> Edit</a>@endif</h3>
    @foreach($data as $presentation)
        <div class="entry">
            @if($presentation->url)
                <h5><a href="{{$presentation->url}}">{{$presentation->title}} <i class="fas fa-link" aria-hidden="true"></i></a></h5>
            @else
                <h5>{{$presentation->title}}</h5>
            @endif
            @if($presentation->start_date)<strong>{{$presentation->start_date}}@if($presentation->end_date)&ndash;{{$presentation->end_date}}@endif</strong>@endif
            @if($presentation->description)
                <em>{!! Purify::clean($presentation->description) !!}</em>
            @endif
        </div>
    @endforeach
    @if($paginated)
        {{ $data->links() }}
    @endif
</section>