@extends('emails.template')

@section('message')
    <p><strong>Dear {{ $name }}</strong>,</p>

    <p><strong>{{ $count }}</strong> undergraduate students have expressed interest in working with @if($delegate)<strong>{{ $faculty->full_name }}</strong>@else you @endif for the <strong>{{ $semester }}</strong> semester.</p>

    @if($delegate)
        <p>You are receiving this notification because {{ $faculty->full_name }} has added you as their delegate on {{ $settings['site_title'] ?? 'Profiles' }}, which allows you to view the following on their behalf.</p>
    @endif

    <p>If you would like to review these undergraduate student research applications, please visit the following page. On this page, you will also be able to delegate review of the applications to another person or indicate if you're not accepting students.</p>

    <p><a href="{{ route('profiles.students', ['profile' => $faculty, 'semester' => $semester]) }}"> Review Undergraduate Student Research Applications</a></p>

@stop
