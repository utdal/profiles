<!-- Name -->
<div class="form-group row">
    <label for="name" class="col-sm-3 col-form-label">
        Primary Name:
        <small id="nameHelp" class="form-text text-muted">The name of the school.</small>
    </label>
    <div class="col-sm-9">
        {!! Form::text('name', null, ['class' => 'form-control', 'required' => 'required']) !!}
    </div>
</div>

<hr>

<!-- Short Name -->
<div class="form-group row">
    <label for="short_name" class="col-sm-3 col-form-label">
        Short Name (acronym):
        <small id="shortNameHelp" class="form-text text-muted">This is used as the URL slug. If the school is known by an acroym, e.g. <em>EECS</em>, this is where to put it.</small>
    </label>
    <div class="col-sm-9">
        {!! Form::text('short_name', null, ['class' => 'form-control', 'required' => 'required']) !!}
    </div>
</div>

<hr>

<!-- Display Name -->
<div class="form-group row">
    <label for="short_name" class="col-sm-3 col-form-label">
        Display Name:
        <small id="displayNameHelp" class="form-text text-muted">This is used wherever the school name is displayed in the interface.</small>
    </label>
    <div class="col-sm-9">
        {!! Form::text('display_name', null, ['class' => 'form-control', 'required' => 'required']) !!}
    </div>
</div>

<hr>

<!-- Aliases -->
<div class="form-group row">
    <label for="short_name" class="col-sm-3 col-form-label">
        Aliases:
        <small id="displayNameHelp" class="form-text text-muted">A semicolon-delimited list of other aliases for the school. This is used when auto-associating a new user with a school. As such, it's a good idea to include departments and majors associated with the school in this list, as well.</small>
    </label>
    <div class="col-sm-9">
        {!! Form::textarea('aliases', null, ['class' => 'form-control']) !!}
    </div>
</div>

<hr>

<!-- Submit Button -->
@unless($readonly ?? false)
<div class="form-group row">
	<div class="offset-sm-3 col-sm-9">
		{!! Form::submit($submitButtonText ?? 'Add School', ['class' => 'btn btn-primary form-control']) !!}
	</div>
</div>
@endunless