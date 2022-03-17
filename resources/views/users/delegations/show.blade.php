@extends('layout')
@section('title', "$user->display_name Delegations")
@section('header')
	@include('nav')
@stop
@section('content')
<div class="container">
    <h1><i class="fas fa-user-friends"></i> {{ $user->display_name }} Delegations</h1>

    <div class="alert alert-info mb-5">
        <p><span class="fa fa-info-circle"></span> You can add a delegate when you want another user to be able to act on your behalf for certain actions.</p>
        <p><span class="fa fa-info-circle"></span> A delegate inherits most of your roles and permissions and can therefore perform many of the same tasks as you.</p>
        <p><span class="fa fa-info-circle"></span> A delegate can optionally also receive the same email notifications as you.</p>
        <p class="mb-0"><span class="fa fa-info-circle"></span> A delegate only has the above abilities during the timeframe that you specify.</p>
    </div>

    {{-- Delegators List --}}
    @if($delegators->isNotEmpty())
        <h3 class="mt-5">Delegators</h3>
        @foreach($delegators as $delegator)
            <div class="card">
                <div class="card-body">
                    {{ $delegator->display_name }} added {{ $user->display_name }} as their delegate
                    <strong>starting</strong> {{ optional($delegator->pivot->starting)->toFormattedDateString() ?? '' }},
                    @if ($delegator->pivot->until)  
                    <strong>until</strong> {{ optional($delegator->pivot->until)->toFormattedDateString() }},
                    @endif
                    <strong>{{ $delegator->pivot->gets_reminders ? 'with' : 'without' }} notifications</strong>
                </div>
            </div>
        @endforeach
    @endif

    <h3 class="mt-5">Delegates</h3>
    
    <livewire:user-delegations :user="$user">

</div>
@stop