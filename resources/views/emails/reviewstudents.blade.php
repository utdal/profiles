@extends('emails.template')

@php
    $primary_color = $settings['primary_color'] ?? '#154734';
@endphp

@section('message')
    <p><strong>Dear {{ $name }}</strong>,</p>

    <p><strong>{{ $count }}</strong> undergraduate students have expressed interest in working with @if($delegate)<strong>{{ $faculty->full_name }}</strong>@else you @endif for the <strong>{{ $semester }}</strong> semester.</p>

    @if($delegate)
        <p>You are receiving this notification because {{ $faculty->full_name }} has added you as their delegate on {{ $settings['site_title'] ?? 'Profiles' }}, which allows you to view the following on their behalf.</p>
    @endif

    <p>If you would like to review these undergraduate student research applications, please visit the following page. On this page, you will also be able to delegate review of the applications to another person or indicate if you're not accepting students.</p>

    <p style="text-align: center; margin: 2rem;">
        <a
            href="{{ route('profiles.students', ['profile' => $faculty, 'semester' => $semester]) }}"
            style="color: white; background-color: {{ $primary_color }}; border: 1px solid {{ $primary_color }}; text-align: center; padding: 0.5rem 1.25rem; line-height: 1.5; font-weight: normal; text-decoration: none; box-shadow: 2px 2px 3px #ccc;"
        >
            Review Undergraduate Student Research Applications →
        </a>
    </p>
@stop
