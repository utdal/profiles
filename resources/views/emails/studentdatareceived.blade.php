@extends('emails.template')

@section('message')

	@if (!$delegate)
		<p>Dear {{ $user['full_name'] }},</p>

		<p>{{ $count }} undergraduate students have expressed interest in working with you for the {{ $semester }} semester. Click here to review these applications: <a href= "#" title="View"></a>
	@else
		<p>Dear {{ $user['full_name'] }},</p>

		<p>You have been delegated to review {{ $count }} applications of undergraduate students that have expressed interest in working with {{ $user['delegator'] }} for the {{ $semester }} semester. Click here to review these applications: <a href= "#" title="View"></a>
	@endif

@stop