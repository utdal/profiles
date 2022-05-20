<?php
$current_user = Auth::user(); 
?>
@extends('layout')
@section('title', 'User Account - ' . $user->display_name)
@section('header')
  @include('nav')
@stop
@section('content')
<div class="container">
  <h2>{{ $user->display_name }}</h2>

  @include('users.panel')

  @if ($current_user)
    <br>
    <p class="d-flex justify-content-around">
      @if ($current_user->can('update', $user))
        <a class="btn btn-primary" href="{{ route('users.edit', [$user->pea]) }}" title="Edit">
          <span class="fas fa-edit"></span> Edit User
        </a>
      @endif
      @if ($current_user->can('delete', $user))
        <a class="btn btn-danger" href="{{ route('users.confirm-delete', [ $user ]) }}" title="Delete">
          <span class="fas fa-trash"></span> Delete User
        </a>
      @endif
    </p>
  @endif
</div>
@stop