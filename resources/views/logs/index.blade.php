@extends('layout')
@section('title', 'Activity Logs')
@section('header')
	@include('nav')
@stop
@section('content')
<div class="container">
    <h1>Activity Logs</h1>

    {!! Form::open(['url' => route('app.logs.index'), 'method' => 'get', 'class' => 'form-inline mb-4']) !!}
    <div class="search input-group input-group-lg">
        <input id="log_search" class="search form-control" type="search" name="search" placeholder="search..." aria-label="Search" value="{{$search}}">
        <div class="input-group-append">
        <button class="btn btn-success" type="submit" data-toggle="replace-icon" data-newicon="fas fa-sync fa-spin" data-inputrequired="#log_search">
            <i class="fas fa-search"></i><span class="sr-only">search</span>
        </button>
        </div>
    </div>
    {!! Form::close() !!}

    <table class="table table-sm table-striped">
        <thead>
            <tr>
                <th>Timestamp</th>
                <th>User</th>
                <th>Event</th>
                <th>Acted On</th>
                <th>ID</th>
                <th>Old Value</th>
                <th>New Value</th>
                <th>URL</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($logs as $log)
            <tr>
                <td>{{ $log->created_at }}</td>
                <td>
                    @if($log->user instanceof App\User)
                        <a href="{{ route('users.show', ['user' => $log->user->pea]) }}">{{ $log->user->id }}: {{ $log->user->display_name }}</a>
                    @else
                        system
                    @endif
                </td>
                <td>{{ $log->event }}</td>
                <td>{{ $log->auditable_type }}</td>
                <td>{{ $log->auditable_id }}</td>
                <td><?php dump($log->old_values) ?></td>
                <td><?php dump($log->new_values) ?></td>
                <td>{{ $log->url }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="paginator">
        {{ $logs->appends(Request::except('page'))->links() }}
    </div>

</div>
@stop