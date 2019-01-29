<?php
$shouldReportToSentry = app()->bound('sentry') && !empty(Sentry::getLastEventID()) && !config('app.debug');
?>
@extends('layout')
@section('header')
	@include('nav')
@stop
@section('content')

<div class="jumbotron">
    <div class="text-center">
        <h2>Something went wrong 😭</h2>

        @if($shouldReportToSentry)
            <p>Error ID: {{ Sentry::getLastEventID() }}</p>
        @endif

    </div>
</div>

@include('errors/list')

@stop

@section('scripts')
    @if($shouldReportToSentry)
        <!-- Sentry JS SDK 2.1.+ required -->
        <script src="https://cdn.ravenjs.com/3.3.0/raven.min.js"></script>

        <script>
        Raven.showReportDialog({
            eventId: '{{ Sentry::getLastEventID() }}',

            // use the public DSN (dont include your secret!)
            dsn: 'https://79be214dedd542088edd97169e7538b6@sentry.io/1057311'
        });
        </script>
    @endif
@stop