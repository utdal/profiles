@extends('layout')
@section('title', "Student Research Application for {$student->full_name}")
@section('header')
	@include('nav')
@stop
@section('content')

<div class="container">
    
    <div class="row mt-4 mt-md-5">
        <div class="col-md-8">
            <h1 class="my-0">
                Student Research Application @if($student->status === 'drafted')<span class="badge rounded-pill badge-secondary">drafted</span>@endif
            </h1>
            <h2 class="mt-0 text-muted">
                for {{ $student->full_name }}
                <small class="col text-muted">
                    <a href="mailto:{{ optional($student->user)->email }}">
                        <i class="fas fa-envelope"></i> {{ optional($student->user)->email }}
                    </a>
                </small>
            </h2>
            <div class="mb-3 mb-md-0">
                @can('update', $student)
                    <a class="btn btn-primary btn-sm" href="{{ route('students.edit', [$student]) }}"><i class="fas fa-edit"></i> Edit</a>
                    @if($student->status === 'drafted')
                        <a class="btn btn-secondary btn-sm" href="{{ route('students.status', ['student' => $student, 'status' => 'submitted']) }}" data-toggle="tooltip" data-placement="auto" title="Submit this student application for consideration"><i class="fas fa-check"></i> Submit</a>
                    @else
                        <a class="btn btn-secondary btn-sm" href="{{ route('students.status', ['student' => $student, 'status' => 'drafted']) }}" data-toggle="tooltip" data-placement="auto" title="Un-submit if you've already joined a research group or want to remove your application from future consideration"><i class="fas fa-undo"></i> Un-submit</a>
                    @endif
                @endcan
                @if(!auth()->user()->owns($student))
                    <livewire:bookmark-button :model="$student">
                @endif
                @can('viewFeedback', $student)
                    <a class="btn btn-primary btn-sm" href="#student_feedback"><i class="fas fa-comment"></i> Feedback</a>
                @endcan
            </div>
        </div>
        <div class="col-md-4 stats alert alert-primary">
            <dl class="row mb-0">
                <dt class="col-sm-4">
                    last updated
                </dt>
                <dd class="col-sm-8">{{ $student->updated_at->toFormattedDateString() }}</dd>

                @if($student->stats)
                    @if($student->stats->last_viewed)
                    <dt class="col-sm-4" title="Date this was last viewed by faculty (or their delegates)" data-toggle="tooltip">
                        last viewed
                    </dt>
                    <dd class="col-sm-8">{{ Carbon\Carbon::parse($student->stats->last_viewed)->toFormattedDateString() }}</dd>
                    @endif

                    @if($student->stats->views)
                    <dt class="col-sm-4" title="Number of times this was viewed by faculty (since this counter was started)" data-toggle="tooltip">
                        viewed
                    </dt>
                    <dd class="col-sm-8">{{ $student->stats->views ?? '0' }} times</dd>
                    @endif

                    @if($student->stats->status)
                    <dt class="col-sm-4" title="How individual faculty have marked this application after reviewing it" data-toggle="tooltip">
                        marked
                    </dt>
                    <dd class="col-sm-8">
                        @foreach($student->stats->status as $stat_status => $stat_count)
                            @if($stat_count)
                                <div>
                                    {{ App\ProfileStudent::$statuses[$stat_status] ?? $stat_status }}
                                    <span class="badge">({{ $stat_count }})</span>
                                </div>
                            @endif
                        @endforeach
                    </dd>
                    @endif

                    @if($student->stats->accepted_by && !empty($student->stats->accepted_by))
                    <dt class="col-sm-4" title="Accepted to research with these labs" data-toggle="tooltip">
                        accepted by
                    </dt>
                    <dd class="col-sm-8">
                        @foreach($student->stats->accepted_by as $accepted_record)
                            <div>{{ $accepted_record['profile_name'] ?? 'n/a' }}</div>
                        @endforeach
                    </dd>
                    @endif
                @endif
            </dl>
        </div>
    </div>
    <hr>

    <fieldset disabled>
        @include('students.form', ['editable' => false])
    </fieldset>

    @can('viewFeedback', $student)
        <hr>
        <h2 id="student_feedback"><i class="fas fa-comment"></i> Feedback</h2>
        <livewire:student-feedback :student="$student">
    @endcan
</div>

@stop