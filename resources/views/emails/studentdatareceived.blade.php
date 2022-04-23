@extends('emails.template')

@section('message')
    <p style="font-weight:bold;"> Dear {{ $name }},</p>

    @if (!$delegate)
        <p><span style="font-weight:bold;">{{ $count }}</span> undergraduate students have expressed interest in working with you for the <span style="font-weight:bold;">{{ $semester }}</span></p>

    @else
        <p>You have been delegated to review <span style="font-weight:bold;">{{ $count }}</span> applications of undergraduate students that have expressed interest in working with <span style="font-weight:bold;">{{ $faculty->full_name }}</span> for the <span style="font-weight:bold;">{{ $semester }}</span> semester.</p>

    @endif

    <p><a href="{{ route('profiles.students', ['profile' => $faculty]) }}"> Review Student Applications</a></p>

@stop
