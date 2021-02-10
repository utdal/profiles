<?php
$shouldReportToSentry = app()->bound('sentry') && !empty(app('sentry')->getLastEventID()) && config('app.sentry_public_dsn') && !config('app.debug');
$sentryEventId = $shouldReportToSentry ? app('sentry')->getLastEventID() : null;
?>
@extends('layout')
@section('title', 'Whoops')
@section('header')
	@include('nav')
@stop
@section('content')

<div class="jumbotron">
    <div class="text-center">
        <h2>Something went wrong 😭</h2>

        @if($shouldReportToSentry)
            <p>Error ID: {{ $sentryEventId }}</p>
        @endif

    </div>
</div>

@include('errors/list')

@stop

@section('scripts')
    @if($shouldReportToSentry)
        <script
            src="https://browser.sentry-cdn.com/6.1.0/bundle.min.js"
            integrity="sha384-T4wn6EUhrkGRYp9a0X2/uXu6frHKrfbSeO4zRRA4KIgrEaJMMRbpunhBtNahdsxW"
            crossorigin="anonymous"
        ></script>
        <script>
        Sentry.init({
            dsn: "{{ config('app.sentry_public_dsn') }}",
        });
        Sentry.showReportDialog({
            eventId: '{{ $sentryEventId }}',
        });
        </script>
    @endif
@stop