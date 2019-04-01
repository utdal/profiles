@extends('layout')
@section('title', 'Add a New User')
@section('header')
  @include('nav')
@stop

@section('content')

@include('errors/list')
<div class="container">
    <h2 class="text-center mt-5">Add a New User / Profile:</h2>

    <div class="row text-center d-flex justify-content-around mt-5">
    <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                {!! Form::open(['route' => ['users.store'], 'method' => 'POST']) !!}
                    <div class="form-group row justify-content-center">
                        <div class="col-sm-10">
                            <input placeholder="{{ $settings['account_name'] ?? 'Username' }}" class="form-control" maxlength="255" name="name" id="name" type="text" required>
                        </div>
                    </div>
                    <div class="form-group row justify-content-center ">
                        <div class="col-sm-10">
                            <input name="create_profile" value="0" type="hidden"> 
                            <input checked="checked" name="create_profile" value="1" type="checkbox">
                            <label class="form-check-label" for="create_profile">Also a create profile</label>
                        </div>
                    </div>
                    <div class="form-group row justify-content-center">
                        <div class="col-md-10">
                            <button type="submit" class="btn btn-success btn-block" style="margin-right: 15px;">
                                <i class="fas fa-plus"></i> Add
                            </button>
                        </div>
                    </div>
                {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('scripts')
<script>
$(document).ready( function () {

  $('#name').focus();

});
</script>
@stop