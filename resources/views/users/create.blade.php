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
                <div class="card-body p-5">
                {!! Form::open(['route' => ['users.store'], 'method' => 'POST']) !!}
                    <div class="form-group">
                        <livewire:directory-search
                            :input_name="'name'"
                            :aria_describedby='"user_search_help"'
                            :required="true"
                        >
                        <small id="user_search_help" class="form-text text-muted mt-2">
                            Required. Start typing a name to search for a person. Then, select them from the list.
                        </small>
                    </div>
                    <div class="form-group">
                        <input name="create_profile" value="0" type="hidden"> 
                        <input checked="checked" id="create_profile" name="create_profile" value="1" type="checkbox">
                        <label class="form-check-label" for="create_profile">Also a create profile</label>
                    </div>
                    <button type="submit" class="btn btn-success btn-block mt-md-5">
                        <i class="fas fa-plus"></i> Add
                    </button>
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