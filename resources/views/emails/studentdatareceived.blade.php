@extends('emails.template')

@section('message')

	<p>Dear {{ $user['full_name'] }},</p>

	<p>{{ $count }} undergraduate students have expressed interest in working with you for the {{ $semester }} semester. Click here to review these applications: <a href= "#" title="View"></a>


@stop