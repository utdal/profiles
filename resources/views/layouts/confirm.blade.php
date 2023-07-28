@extends('layout')

@section('title')
    @yield('confirm_title', 'Are you sure?')
@stop

@section('header')
  @include('nav')
@stop

@section('content')
    <div class="container">
        <div class="row text-center mt-5 d-flex justify-content-around">
            <div class="col-md-8">
                <div class="card bg-danger shadow-lg">
                    <div class="card-header">
                        <h3 class="text-white bold">@yield('confirm_title', 'Are you sure?')</h3>
                    </div>
                    <div class="card-body bg-white">
                        @yield('form')
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('cancel')?.addEventListener('click', () => window.history.back());
    });
</script>
@endpush
