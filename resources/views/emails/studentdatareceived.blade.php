@extends('emails.template')

@section('message')
	<p>Dear {{ $name }},</p>

	@if (!$delegate)
		<p>{{ $count }} undergraduate students have expressed interest in working with you for the {{ $semester }} semester.</p>

	@else
		<p>You have been delegated to review {{ $count }} applications of undergraduate students that have expressed interest in working with {{ $faculty->full_name }} for the {{ $semester }} semester.</p>
		
	@endif
		
	<p><a href="{{ route('profiles.students', ['profile' => $faculty]) }}"> Review Student Applications</a></p>

@stop
