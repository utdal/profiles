<?php
$shouldReportToSentry = app()->bound('sentry') && !empty(Sentry::getLastEventID()) && config('app.sentry_public_dsn') && !config('app.debug');
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
        <script src="https://browser.sentry-cdn.com/5.0.7/bundle.min.js" crossorigin="anonymous"></script>
        <script>
        Sentry.init({
            dsn: "{{ config('app.sentry_public_dsn') }}",
        });
        Sentry.showReportDialog({
            eventId: '{{ Sentry::getLastEventID() }}',
        });
        </script>
    @endif
@stop