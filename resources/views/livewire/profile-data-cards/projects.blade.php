<section id="projects" class="card" aria-labelledby="projects-heading">
    <h2 id="projects-heading"><i class="fas fa-tasks" aria-hidden="true"></i> Projects @if($editable)<a class="btn btn-primary btn-sm" href="{{ route('profiles.edit', [$profile->slug, 'projects']) }}" aria-label="Edit Projects"><i class="fas fa-edit"></i> Edit</a>@endif</h2>
    <ul class="list-unstyled">
        @foreach($data as $project)
            <li class="entry">
                <article>
                    @if($project->url)
                        <h3>
                            <a href="{{$project->url}}" target="_blank" class="has-external-link-icon">
                                <span class="has-external-link-icon">{{$project->title}}</span>
                                <i class="fas fa-external-link-alt" aria-hidden="true"></i>
                                <span class="sr-only"> (opens in a new tab)</span>
                            </a>
                        </h3>
                    @else
                        <h3>{{$project->title}}</h3>
                    @endif
                    @if($project->start_date)<strong>{{$project->start_date}}@if($project->end_date)&ndash;{{$project->end_date}}@endif</strong>@endif
                    @if($project->description)
                        <em>{!! Purify::clean($project->description) !!}</em>
                    @endif
                </article>
            </li>
        @endforeach
    </ul>
    @if($paginated)
        {{ $data->links() }}
    @endif
</section>