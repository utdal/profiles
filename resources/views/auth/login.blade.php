@extends('layout')
@section('title', 'Login')
@section('header')
    @include('nav')
@stop

@section('content')
<div class="container container-fluid" style="margin-top: 20vh; margin-bottom: 30vh;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    Login
                </div>
                <div class="card-body">
                    @if ($errors->isNotEmpty())
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> There were some problems with your input.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form class="form-horizontal" role="form" method="POST" action="{{ route('login') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div class="form-group row justify-content-center">
                            <label class="col-md-3 col-form-label text-md-right" for="name">
                                {{ $settings['account_name'] ?? 'Username' }}
                            </label>
                            <div class="col-md-7">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" aria-label="Username" autocomplete="username">
                                </div>
                            </div>
                        </div>

                        <div class="form-group row justify-content-center">
                            <label class="col-md-3 col-form-label text-md-right" for="password">Password</label>
                            <div class="col-md-7">
                                <input type="password" class="form-control" id="password" name="password" autocomplete="current-password">
                            </div>
                        </div>

                        <div class="form-group row justify-content-center">
                            <div class="col-md-7 offset-md-3">
                                <div class="form-check">
                                    <input class="form-check-imput" type="checkbox" id="remember" name="remember">
                                    <label class="form-check-label" for="remember">Remember Me</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row justify-content-center">
                            <div class="col-md-4 offset-md-3">
                                <button type="submit" class="btn btn-success btn-block" style="margin-right: 15px;">
                                    <i class="fas fa-sign-in-alt"></i> Login
                                </button>
                            </div>
                            <div class="col-md-3 d-flex align-items-center justify-content-end">
                                @if(isset($settings['forgot_password_url']))
                                <a href="{{ $settings['forgot_password_url'] ?? '#' }}" target="_blank">
                                    <small>Forgot Your Password?</small>
                                </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // focus the name field if it's empty
    $username = $('#name');
    if (! $username.val()) {
        $username.focus();
    }
});
</script>
@endsection
