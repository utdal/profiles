@extends('layout')
@section('title', 'Edit User - ' . $user->name)
@section('header')
	@include('nav')
@stop
@section('content')
<div class="container">
	<h2>Edit User {{ $user->name }}</h2>

	@include('errors/has')

	@if (Session::has('admin'))
		<div class="alert alert-success alert-dismissable" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			{{ session('admin') }}
		</div>
	@endif

	@if (Session::has('editor'))
		<div class="alert alert-success alert-dismissable" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			{{ session('editor') }}
		</div>
	@endif

	{!! Form::model($user, ['method' => 'PATCH', 'route' => ['users.update', $user->pea], 'class' => 'form-horizontal', 'files' => true]) !!}
	<!-- NetID (name) -->
	<div class="form-group {{ ($errors->has('name') ?  'has-error' : '') }}">
		{!! Form::label('name', 'NetID:', ['class' => 'col-sm-2 control-label']) !!}
		<div class="col-sm-9">
		{!! Form::text('name', null, ['class' => 'form-control']) !!}
		<span class="text-danger">{!! $errors->first('name') !!}</span>
		</div>
	</div>
	<!-- pea -->
	<div class="form-group {{ ($errors->has('pea') ?  'has-error' : '') }}">
		{!! Form::label('pea', 'PEA:', ['class' => 'col-sm-2 control-label']) !!}
		<div class="col-sm-9">
		{!! Form::text('pea', null, ['class' => 'form-control']) !!}
		<span class="text-danger">{!! $errors->first('pea') !!}</span>
		</div>
	</div>
	<!-- Display Name -->
	<div class="form-group {{ ($errors->has('display_name') ?  'has-error' : '') }}">
		{!! Form::label('display_name', 'Display Name:', ['class' => 'col-sm-2 control-label']) !!}
		<div class="col-sm-9">
		{!! Form::text('display_name', null, ['class' => 'form-control']) !!}
		<span class="text-danger">{!! $errors->first('display_name') !!}</span>
		</div>
	</div>
	<!-- firstname -->
	<div class="form-group {{ ($errors->has('firstname') ?  'has-error' : '') }}">
		{!! Form::label('firstname', 'First Name:', ['class' => 'col-sm-2 control-label']) !!}
		<div class="col-sm-9">
		{!! Form::text('firstname', null, ['class' => 'form-control']) !!}
		<span class="text-danger">{!! $errors->first('firstname') !!}</span>
		</div>
	</div>
	<!-- lastname -->
	<div class="form-group {{ ($errors->has('lastname') ?  'has-error' : '') }}">
		{!! Form::label('lastname', 'Last Name:', ['class' => 'col-sm-2 control-label']) !!}
		<div class="col-sm-9">
		{!! Form::text('lastname', null, ['class' => 'form-control']) !!}
		<span class="text-danger">{!! $errors->first('lastname') !!}</span>
		</div>
	</div>
	<!-- email -->
	<div class="form-group {{ ($errors->has('email') ?  'has-error' : '') }}">
		{!! Form::label('email', 'Email:', ['class' => 'col-sm-2 control-label']) !!}
		<div class="col-sm-9">
		{!! Form::text('email', null, ['class' => 'form-control']) !!}
		<span class="text-danger">{!! $errors->first('email') !!}</span>
		</div>
	</div>
	<!-- title -->
	<div class="form-group {{ ($errors->has('title') ?  'has-error' : '') }}">
		{!! Form::label('title', 'Title:', ['class' => 'col-sm-2 control-label']) !!}
		<div class="col-sm-9">
		{!! Form::text('title', null, ['class' => 'form-control']) !!}
		<span class="text-danger">{!! $errors->first('title') !!}</span>
		</div>
	</div>
	<!-- department -->
	<div class="form-group {{ ($errors->has('department') ?  'has-error' : '') }}">
		{!! Form::label('department', 'Department:', ['class' => 'col-sm-2 control-label']) !!}
		<div class="col-sm-9">
		{!! Form::text('department', null, ['class' => 'form-control']) !!}
		<span class="text-danger">{!! $errors->first('department') !!}</span>
		</div>
	</div>
	<!-- School -->
	<div class="form-group {{ ($errors->has('school_id') ?  'has-error' : '') }}">
		{!! Form::label('school_id', 'School:', ['class' => 'col-sm-2 control-label']) !!}
		<div class="col-sm-9">
		{!! Form::select('school_id', [null => "None"] + $schools, null, ['class' => 'form-control']) !!}
		<span class="text-danger">{!! $errors->first('school_id') !!}</span>
		</div>
	</div>
	<!-- Roles -->
	<div class="form-group{{ ($errors->has('roles') ?  'has-error' : '') }}">
		{!! Form::label('roles', 'roles:', ['class' => 'col-sm-2 control-label']) !!}
		<div class="col-sm-9">
			<div class="checkbox">
				@foreach($roles as $role)
				<?php
					$role_options = [];
					if ($role->name === $school_editor_role->name) {
						$role_options['data-toggle'] = 'show';
						$role_options['data-toggle-target'] = '#editor_schools_toggle';
					} elseif ($role->name === $department_editor_role->name) {
						$role_options['data-toggle'] = 'show';
						$role_options['data-toggle-target'] = '#editor_departments_toggle';
					}
				?>
				<label class="checkbox-inline" title="{{ $role->description }}">
					{!! Form::checkbox("role_list[{$role->id}]",$role->id,$user->hasRole($role->name), $role_options) !!}
					{{ $role->display_name }}
				</label>
				@endforeach
			</div>
			<span class="text-danger">{!! $errors->first('roles') !!}</span>
		</div>
	</div>
	<!-- Editor schools -->
	<?php $school_editor_field = "role_list[{$school_editor_role->id}][options][schools][]" ?>
	<div id="editor_schools_toggle" class="subform form-group{{ ($errors->has($school_editor_field) ?  'has-error' : '') }}">
		{!! Form::label($school_editor_field, 'Editor for school:', ['class' => 'col-sm-6 control-label']) !!}
		<div class="col-sm-6">
			{!! Form::select($school_editor_field, $schools, $user->roleOptions('school_profiles_editor', 'schools'), ['class' => 'form-control', 'placeholder' => 'Select school(s)', 'multiple' => 'multiple']) !!}
			<span class="text-danger">{!! $errors->first('editor_schools[]') !!}</span>
		</div>
	</div>
	<!-- Editor departments -->
	<?php $department_editor_field = "role_list[{$department_editor_role->id}][options][departments][]" ?>
	<div id="editor_departments_toggle" class="subform form-group{{ ($errors->has($department_editor_field) ?  'has-error' : '') }}">
		{!! Form::label($department_editor_field, 'Editor for departments:', ['class' => 'col-sm-6 control-label']) !!}
		<div class="col-sm-6">
			{!! Form::select($department_editor_field, $departments, $user->roleOptions('department_profiles_editor', 'departments'), ['class' => 'form-control', 'placeholder' => 'Select department(s)', 'multiple' => 'multiple']) !!}
			<span class="text-danger">{!! $errors->first('editor_departments') !!}</span>
		</div>
	</div>
	<!-- Submit Button -->
	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-7">
			{!! Form::submit('Update User', ['class' => 'btn btn-primary form-control']) !!}
		</div>
	</div>
	{!! Form::close() !!}
</div>
@stop
