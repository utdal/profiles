@extends('layout')
@section('title', 'Edit User - ' . $user->name)
@section('header')
	@include('nav')
	@push('breadcrumbs')
		<li class="breadcrumb-item active">
			Admin
		</li>
		<li class="breadcrumb-item active">
			@can('viewAdminIndex', App\User::class)
				<a href="{{ route('users.index') }}">All Users</a>
			@else
				Users
			@endcan
		</li>
		<li class="breadcrumb-item active">
			@can('view', $user)
				<a href="{{ route('users.show', ['user' => $user]) }}">{{ $user->display_name }}</a>
			@else
				{{ $user->display_name }}
			@endcan
		</li>
		<li class="breadcrumb-item active" aria-current="page">
			Edit
		</li>
	@endpush
	@include('breadcrumbs')
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

	{{ html()->modelForm($user,'PATCH',route('users.update', $user->pea))->class('form-horizontal')->acceptsFiles()->open() }}
	<!-- Username (name) -->
	<div class="form-group {{ ($errors->has('name') ?  'has-error' : '') }}">
		{{ html()->label($settings['account_name'] ?? 'Username', 'name')->class('col-sm-2 control-label') }}
		<div class="col-sm-9">
		{{ html()->text('name', null)->class('form-control') }}
		<span class="text-danger">{!! $errors->first('name') !!}</span>
		</div>
	</div>
	<!-- pea -->
	<div class="form-group {{ ($errors->has('pea') ?  'has-error' : '') }}">
		{{ html()->label('URL Name', 'pea')->class('col-sm-2 control-label') }}
		<div class="col-sm-9">
		{{ html()->text('pea', null)->class('form-control') }}
		<span class="text-danger">{!! $errors->first('pea') !!}</span>
		</div>
	</div>
	<!-- Display Name -->
	<div class="form-group {{ ($errors->has('display_name') ?  'has-error' : '') }}">
		{{ html()->label('Display Name:', 'display_name')->class('col-sm-2 control-label') }}
		<div class="col-sm-9">
		{{ html()->text('display_name', null)->class('form-control') }}
		<span class="text-danger">{!! $errors->first('display_name') !!}</span>
		</div>
	</div>
	<!-- firstname -->
	<div class="form-group {{ ($errors->has('firstname') ?  'has-error' : '') }}">
		{{ html()->label('First Name:', 'firstname')->class('col-sm-2 control-label') }}
		<div class="col-sm-9">
		{{ html()->text('firstname', null)->class('form-control') }}
		<span class="text-danger">{!! $errors->first('firstname') !!}</span>
		</div>
	</div>
	<!-- lastname -->
	<div class="form-group {{ ($errors->has('lastname') ?  'has-error' : '') }}">
		{{ html()->label('Last Name:', 'lastname')->class('col-sm-2 control-label') }}
		<div class="col-sm-9">
		{{ html()->text('lastname', null)->class('form-control') }}
		<span class="text-danger">{!! $errors->first('lastname') !!}</span>
		</div>
	</div>
	<!-- email -->
	<div class="form-group {{ ($errors->has('email') ?  'has-error' : '') }}">
		{{ html()->label('Email:', 'email')->class('col-sm-2 control-label') }}
		<div class="col-sm-9">
		{{ html()->text('email', null)->class('form-control') }}
		<span class="text-danger">{!! $errors->first('email') !!}</span>
		</div>
	</div>
	<!-- title -->
	<div class="form-group {{ ($errors->has('title') ?  'has-error' : '') }}">
		{{ html()->label('Title:', 'title')->class('col-sm-2 control-label' )}}
		<div class="col-sm-9">
		{{ html()->text('title', null)->class('form-control') }}
		<span class="text-danger">{!! $errors->first('title') !!}</span>
		</div>
	</div>
	<!-- department -->
	<div class="form-group {{ ($errors->has('department') ?  'has-error' : '') }}">
		{{ html()->label('Department:', 'department')->class('col-sm-2 control-label') }}
		<div class="col-sm-9">
		{{ html()->text('department', null)->class('form-control') }}
		<span class="text-danger">{!! $errors->first('department') !!}</span>
		</div>
	</div>
	<!-- School -->
	<div class="form-group {{ ($errors->has('school_id') ?  'has-error' : '') }}">
		{{ html()->label('School:', 'school_id')->class('col-sm-2 control-label') }}
		<div class="col-sm-9">
		{{ html()->select('school_id', [null => "None"] + $schools, null)->class('form-control') }}
		<span class="text-danger">{!! $errors->first('school_id') !!}</span>
		</div>
	</div>
	<!-- Additionals -->
	<div class="form-group">
		<button type="button" class="btn btn-link" data-toggle="collapse" data-target="#additional" aria-expanded="false" aria-controls="other">
			<strong>Additional departments and schools <i class="fas fa-caret-right"></i></strong>
		</button>
		<div class="collapse col-sm-9" id="additional">
			<div class="form-group col-sm-12">
				{{ html()->label('Additional Departments:', 'additional_departments')->class('control-label') }}
				{{ html()->text('additional_departments', implode(',', $user->setting->additional_departments ?? []))->class('form-control') }}
			</div>
			<div class="form-group col-sm-12">
				{{ html()->label('Additional Schools:', 'additional_schools')->class('control-label') }}
				<div class="col-sm-12">
					@foreach($schools as $school_id => $school_name)
						<label class="checkbox">
							{{ html()->checkbox("additional_schools[{$school_id}]", optional($user->setting->additional_schools ?? null)->contains('id', $school_id), $school_name) }}
							{{ $school_name }}
						</label>
					@endforeach
				</div>
			</div>
		</div>
	</div>
	<!-- Sync -->
	<div class="form-group">
		<button type="button" class="btn btn-link" data-toggle="collapse" data-target="#synchronization" aria-expanded="false" aria-controls="other">
			<strong>Additional synchronization settings <i class="fas fa-caret-right"></i></strong>
		</button>
		<div class="collapse col-sm-9" id="synchronization">
			<div class="form-group {{ ($errors->has('no_sync') ?  'has-error' : '') }}">
				{{ html()->label('Exclude from sync on login:', 'no_sync')->class('col-sm-4 control-label') }}
				<div class="col-sm-8">
					<div class="checkbox-inline">
						<label class="checkbox-inline" title="User no-sync setting">
							{{ html()->checkbox("no_sync[attributes]", $user->setting->no_sync['attributes'] ?? false, 1) }}
							User attributes
						</label>
						<label class="checkbox-inline" title="User no-sync setting">
							{{ html()->checkbox("no_sync[roles]", $user->setting->no_sync['roles'] ?? false, 1) }}
							User roles
						</label>
						<label class="checkbox-inline" title="User no-sync setting">
							{{ html()->checkbox("no_sync[school]", $user->setting->no_sync['school'] ?? false, 1) }}
							User school
						</label>
					</div>
					<span class="text-danger">{!! $errors->first('no_sync') !!}</span>
				</div>
			</div>
		</div>
	</div>
	<!-- Roles -->
	<div class="form-group {{ ($errors->has('roles') ?  'has-error' : '') }}">
		{{ html()->label('Roles:', 'roles')->class('col-sm-2 control-label') }}
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
					{{ html()->checkbox("role_list[{$role->id}]", $user->hasRole($role->name), $role->id)->attributes($role_options) }}
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
		{{ html()->label('Editor for school:', $school_editor_field)->class('col-sm-6 control-label') }}
		<div class="col-sm-6">
			{{ html()->select($school_editor_field, $schools, $user->roleOptions('school_profiles_editor', 'schools'))->placeholder('Select school(s)')->multiple()->class('form-control') }}
			<span class="text-danger">{!! $errors->first('editor_schools[]') !!}</span>
		</div>
	</div>
	<!-- Editor departments -->
	<?php $department_editor_field = "role_list[{$department_editor_role->id}][options][departments][]" ?>
	<div id="editor_departments_toggle" class="subform form-group{{ ($errors->has($department_editor_field) ?  'has-error' : '') }}">
		{{ html()->label('Editor for departments:', $department_editor_field)->class('col-sm-6 control-label') }}
		<div class="col-sm-6">
			{{ html()->select($department_editor_field, $departments, $user->roleOptions('department_profiles_editor', 'departments'))->placeholder('Select department(s)')->multiple()->class('form-control') }}
			<span class="text-danger">{!! $errors->first('editor_departments') !!}</span>
		</div>
	</div>
	<!-- Submit Button -->
	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-7">
			{{ html()->submit('Update User')->class('btn btn-primary form-control') }}
		</div>
	</div>
	{{ html()->closeModelForm() }}
</div>
@stop
@section('scripts')
<script>
	$('[name^=additional_departments]').tagsinput({
		typeaheadjs: {
			name: 'departments',
			source: (new Bloodhound({
				datumTokenizer: Bloodhound.tokenizers.whitespace,
				queryTokenizer: Bloodhound.tokenizers.whitespace,
				local: {!! json_encode(array_values($departments)) !!},
			})).ttAdapter(),
		},
		trimValue: true,
	});
</script>
@stop
