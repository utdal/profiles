@extends('layout')
@section('title', 'FAQ')
@section('header')
  @include('nav')
@stop

@section('content')

<div class="container">
<h2>Frequently Asked Questions</h2>

{!! $settings['faq'] ?? 'No FAQs yet.' !!}

</div>
@stop