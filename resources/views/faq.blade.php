@extends('layout')
@section('title', 'FAQ')
@section('header')
    @include('nav')
    @push('breadcrumbs')
        <li class="breadcrumb-item active" aria-current="page">
            FAQ
        </li>
    @endpush
    @include('breadcrumbs')
@stop

@section('content')

<div class="container">
<h2>Frequently Asked Questions</h2>

{!! $settings['faq'] ?? 'No FAQs yet.' !!}

</div>
@stop