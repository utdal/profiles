<section id="projects" class="card">
    <h2><i class="fas fa-tasks" aria-hidden="true"></i> Projects @if($editable)<a class="btn btn-primary btn-sm" href="{{ route('profiles.edit', [$profile->slug, 'projects']) }}" aria-label="Edit Projects"><i class="fas fa-edit"></i> Edit</a>@endif</h2>
    @foreach($data as $project)
        <div class="entry">
            @if($project->url)
                <h3><a href="{{$project->url}}">{{$project->title}} <i class="fas fa-link" aria-hidden="true"></i></a></h3>
            @else
                <h3>{{$project->title}}</h3>
            @endif
            @if($project->start_date)<strong>{{$project->start_date}}@if($project->end_date)&ndash;{{$project->end_date}}@endif</strong>@endif
            @if($project->description)
                <em>{!! Purify::clean($project->description) !!}</em>
            @endif
        </div>
    @endforeach
    @if($paginated)
        {{ $data->links() }}
    @endif
</section>