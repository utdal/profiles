<h1>Edit <a href="{{ action('ProfilesController@show', [$profile->slug]) }}">{{$profile->name}}</a>'s Contact Information</h1>
<div class="row">
	@foreach($data as $info)
		<div class="col col-md-4">
			<label for="file">Icon</label>
			<img class="profile_photo" src="{{ $profile->image_url }}" alt="{{ $profile->full_name }}">
			{!! Form::open(['url' => route('profiles.update-banner', [$profile->slug]), 'method' => 'POST', 'files' => true]) !!}
			<label for="banner">Banner</label>
			<img id="banner-img" class="profile_photo" src="{{ $profile->banner_url }}" />
			<br />
			<br />
			<div class="control-group">
				<div class="controls">
					{!! Form::file('banner_image', ['id' => 'banner', 'name' => 'banner_image', 'required' => 'true', 'accept' => 'image/*', 'class' => 'd-none form-control']) !!}
					<label for="banner" class="btn btn-secondary btn-block"><i class="fas fa-plus"></i> Select Image</label>
					{!! Form::inlineErrors('banner_image') !!}
				</div>
			</div>
			<button type="submit" class="btn btn-primary btn-block" data-toggle="replace-icon" data-newicon="fas fa-sync fa-spin" data-inputrequired="#banner">
				<i class="fas fa-upload"></i> Replace Image
			</button>
			{!! Form::close() !!}
			<br>
			<br>
		</div>
		<div class="col col-md-8 col-12">
			{!! Form::model($profile, ['route' => ['profiles.update', 'profile' => $profile, 'section' => 'information']]) !!}
			<div class="form-group">
				{!! Form::label('full_name', 'Display Name') !!}
				{!! Form::text('full_name', $profile->full_name, ['class' => 'form-control', 'required']) !!}
				{!! Form::inlineErrors('full_name') !!}
			</div>
			<div class="form-group">
				<input type="hidden" name="data[{{$info->id}}][id]"  value="{{$info->id}}" />
				<label for="data[{{$info->id}}][data][title]">Title</label>
				<input type="text" class="form-control" name="data[{{$info->id}}][data][title]" id="data[{{$info->id}}][data][title]" value="{{$info->title}}" required />
				{!! Form::inlineErrors("data.".$info->id.".data.title") !!}
			</div>
			<div class="form-group">
				<label for="data[{{$info->id}}][data][distinguished_title]">Distinguished Title</label>
				<input type="text" class="form-control" name="data[{{$info->id}}][data][distinguished_title]" id="data[{{$info->id}}][data][distinguished_title]" value="{{$info->distinguished_title}}"  />
			</div>
			<div class="form-group">
				<label for="data[{{$info->id}}][data][secondary_title]">Secondary Title</label>
				<input type="text" class="form-control" name="data[{{$info->id}}][data][secondary_title]" id="data[{{$info->id}}][data][secondary_title]" value="{{$info->secondary_title}}"  />
			</div>
			<div class="form-group">
				<label for="data[{{$info->id}}][data][tertiary_title]">Tertiary Title</label>
				<input type="text" class="form-control" name="data[{{$info->id}}][data][tertiary_title]" id="data[{{$info->id}}][data][tertiary_title]" value="{{$info->tertiary_title}}"  />
			</div>
			<div class="form-group">
				<label for="data[{{$info->id}}][data][profile_summary]">Profile Summary</label><small class="gray"> (Limit 280 Characters) </small>
				<textarea type="text" class="form-control" name="data[{{$info->id}}][data][profile_summary]" id="data[{{$info->id}}][data][profile_summary]" maxlength="280">{{$info->profile_summary}}</textarea>
			</div>
			<div class="row">
					<div class="form-group col col-sm-6 col-12">
						<label for="data[{{$info->id}}][data][email]">Email</label>
						<input type="email" class="form-control" name="data[{{$info->id}}][data][email]" id="data[{{$info->id}}][data][email]" value="{{$info->email}}"  />
					</div>
					<div class="form-group col col-sm-6 col-12">
						<label for="data[{{$info->id}}][data][phone]">Phone</label>
						<input type="tel" class="form-control" name="data[{{$info->id}}][data][phone]" id="data[{{$info->id}}][data][phone]" value="{{$info->phone}}"  />
					</div>
			</div>
			<div class="row">
				<div class="form-group col col-sm-6 col-12">
					<label for="data[{{$info->id}}][data][location]">Location</label>
					<input type="text" class="form-control" name="data[{{$info->id}}][data][location]" id="data[{{$info->id}}][data][location]" value="{{$info->location}}"  />
				</div>
			</div>
			<div class="row">
				<div class="form-group col col-sm-6 col-12">
					<label for="data[{{$info->id}}][data][url]">Primary URL</label>
					<input type="url" class="form-control" name="data[{{$info->id}}][data][url]" id="data[{{$info->id}}][data][url]" value="{{$info->url}}"  />
				</div>
				<div class="form-group col col-sm-6 col-12">
					<label for="data[{{$info->id}}][data][url_name]">Primary URL Title</label>
					<input type="text" class="form-control" name="data[{{$info->id}}][data][url_name]" id="data[{{$info->id}}][data][url_name]" value="{{$info->url_name}}"  />
				</div>
			</div>
			<div class="row">
				<div class="form-group col col-sm-6 col-12">
					<label for="data[{{$info->id}}][data][url_name]">Second URL</label>
					<input type="url" class="form-control" name="data[{{$info->id}}][data][secondary_url]" id="data[{{$info->id}}][data][secondary_url]" value="{{$info->secondary_url}}"  />
				</div>
				<div class="form-group col col-sm-6 col-12">
					<label for="data[{{$info->id}}][data][url_name]">Second URL Title</label>
					<input type="text" class="form-control" name="data[{{$info->id}}][data][secondary_url_name]" id="data[{{$info->id}}][data][secondary_url_name]" value="{{$info->secondary_url_name}}"  />
				</div>
			</div>
			<div class="row">
				<div class="form-group col col-sm-6 col-12">
					<label for="data[{{$info->id}}][data][url_name]">Third URL</label>
					<input type="url" class="form-control" name="data[{{$info->id}}][data][tertiary_url]" id="data[{{$info->id}}][data][tertiary_url]" value="{{$info->tertiary_url}}"  />
				</div>
				<div class="form-group col col-sm-6 col-12">
					<label for="data[{{$info->id}}][data][url_name]">Third URL Title</label>
					<input type="text" class="form-control" name="data[{{$info->id}}][data][tertiary_url_name]" id="data[{{$info->id}}][data][tertiary_url_name]" value="{{$info->tertiary_url_name}}"  />
				</div>
			</div>
			<div class="row">
				<div class="form-group col col-sm-6 col-12">
					<label for="data[{{$info->id}}][data][url_name]">Fourth URL</label>
					<input type="url" class="form-control" name="data[{{$info->id}}][data][quaternary_url]" id="data[{{$info->id}}][data][quaternary_url]" value="{{$info->quaternary_url}}"  />
				</div>
				<div class="form-group col col-sm-6 col-12">
					<label for="data[{{$info->id}}][data][url_name]">Fourth URL Title</label>
					<input type="text" class="form-control" name="data[{{$info->id}}][data][quaternary_url_name]" id="data[{{$info->id}}][data][quaternary_url_name]" value="{{$info->quaternary_url_name}}"  />
				</div>
			</div>
			<div class="row">
				<div class="form-group col col-sm-6 col-12">
					<label for="data[{{$info->id}}][data][url_name]">Fifth URL</label>
					<input type="url" class="form-control" name="data[{{$info->id}}][data][quinary_url]" id="data[{{$info->id}}][data][quinary_url]" value="{{$info->quinary_url}}"  />
				</div>
				<div class="form-group col col-sm-6 col-12">
					<label for="data[{{$info->id}}][data][url_name]">Fifth URL Title</label>
					<input type="text" class="form-control" name="data[{{$info->id}}][data][quinary_url_name]" id="data[{{$info->id}}][data][quinary_url_name]" value="{{$info->quinary_url_name}}"  />
				</div>
			</div>
			<div class="form-group">
				<label for="data[{{$info->id}}][data][orc_id]">ORCID</label>
				<input type="text" class="form-control" name="data[{{$info->id}}][data][orc_id]" id="data[{{$info->id}}][data][orc_id]" value="{{$info->orc_id}}"  onkeyup="javascript:$(this).val($(this).val().replace('https://orcid.org/', '').replace('http://orcid.org/', ''));" pattern="^[0-9]{4}-[0-9]{4}-[0-9]{4}-[0-9]{3}[0-9X]$"/>
			</div>
			<fieldset id="orcid_managed" class="form-group row my-3 py-4">
				<div class="col col-12 col-xl-7">
					<div class="form-group form-check p-0">
						<input type="hidden" name="data[{{$info->id}}][data][orc_id_managed]" value="0">
						<input
							type="checkbox"
							name="data[{{$info->id}}][data][orc_id_managed]"
							id="data[{{$info->id}}][data][orc_id_managed]"
							@checked(old("data.{$info->id}.data.orc_id_managed", $info->orc_id_managed))
							value="1"
						>
						<label class="form-check-label" for="data[{{$info->id}}][data][orc_id_managed]">Auto-update Publications</label>
					</div>
				</div>
				<div class="col col-12 col-xl-5">
					<p class="text-muted">Refresh all publications via ORCID. All previous publications will be removed and fresh data will be pulled in at regular intervals. Keep unchecked to manually edit your publications.</p>
				</div>
			</fieldset>
			<fieldset id="fancy_header" class="form-group row my-3 py-4 border-top">
				<div class="col col-12 col-xl-7">
					<div class="form-group form-check p-0">
						<input type="hidden" name="data[{{$info->id}}][data][fancy_header]" value="0">
						<input
							type="checkbox"
							name="data[{{$info->id}}][data][fancy_header]"
							id="data[{{$info->id}}][data][fancy_header]"
							@checked(old("data.{$info->id}.data.fancy_header", $info->fancy_header))
							value="1"
							data-toggle="show"
							data-toggle-target="#fancy_header_options"
						>
						<label class="form-check-label" for="data[{{$info->id}}][data][fancy_header]">Fancy Header</label>
					</div>
					{{-- reset sub-options if main option is unchecked --}}
					<input type="hidden" name="data[{{$info->id}}][data][fancy_header_right]" value="0">
					<div
						id="fancy_header_options"
						class="border-left ml-3"
						@style([
							'display: none' => !old('data.show_accepting_students', $info->show_accepting_students)
						])
					>
						<div class="form-group form-check">
							<input
								type="checkbox"
								name="data[{{$info->id}}][data][fancy_header_right]"
								id="data[{{$info->id}}][data][fancy_header_right]"
								@checked(old("data.{$info->id}.data.fancy_header_right", $info->fancy_header_right))
								value="1"
							>
							<label class="form-check-label" for="data[{{$info->id}}][data][fancy_header_right]">Align Header Right</label>
						</div>
					</div>
				</div>
				<div class="col col-12 col-xl-5">
					<p class="text-muted">This will use a full-width header style - please make sure uploaded banner image is of sufficient quality!</p>
				</div>
			</fieldset>
			<fieldset id="show_accepting" class="form-group row my-3 py-4 border-top">
				<div class="col col-12 col-xl-7">
					<div class="form-group form-check p-0">
						<input type="hidden" name="data[{{$info->id}}][data][show_accepting_students]" value="0">
						<input
							type="checkbox"
							name="data[{{$info->id}}][data][show_accepting_students]"
							id="data[{{$info->id}}][data][show_accepting_students]"
							@checked(old("data.{$info->id}.data.show_accepting_students", $info->show_accepting_students))
							value="1"
							data-toggle="show"
							data-toggle-target="#accepting_student_options"
						>
						<label class="form-check-label" for="data[{{$info->id}}][data][show_accepting_students]">Show that I'm accepting students</label>
					</div>
					{{-- reset sub-options if main option is unchecked --}}
					<input type="hidden" name="data[{{$info->id}}][data][accepting_students]" value="0">
					<input type="hidden" name="data[{{$info->id}}][data][accepting_grad_students]" value="0">
					<div
						id="accepting_student_options"
						class="border-left ml-3"
						@style([
							'display: none' => !old('data.show_accepting_students', $info->show_accepting_students)
						])
					>
						<div class="form-group form-check">
							<input
								type="checkbox"
								name="data[{{$info->id}}][data][accepting_students]"
								id="data[{{$info->id}}][data][accepting_students]"
								@checked(old("data.{$info->id}.data.accepting_students", $info->accepting_students))
								value="1"
							>
							<label class="form-check-label" for="data[{{$info->id}}][data][accepting_students]">Accepting undergrad students</label>
						</div>
						<div class="form-group form-check">
							<input
								type="checkbox"
								name="data[{{$info->id}}][data][accepting_grad_students]"
								id="data[{{$info->id}}][data][accepting_grad_students]"
								@checked(old("data.{$info->id}.data.accepting_grad_students", $info->accepting_grad_students))
								value="1"
							>
							<label class="form-check-label" for="data[{{$info->id}}][data][accepting_grad_students]">Accepting grad students</label>
						</div>
					</div>
				</div>
				<div class="col col-12 col-xl-5">
					<p class="text-muted">This will show a standard note on your profile that you're currently accepting students of the specified type(s).</p>
				</div>
			</fieldset>
			<fieldset id="show_not_accepting" class="form-group row my-3 py-4 border-top">
				<div class="col col-12 col-xl-7">
					<div class="form-group form-check p-0">
						<input type="hidden" name="data[{{$info->id}}][data][show_not_accepting_students]" value="0">
						<input
							type="checkbox"
							name="data[{{$info->id}}][data][show_not_accepting_students]"
							id="data[{{$info->id}}][data][show_not_accepting_students]"
							@checked(old("data.{$info->id}.data.show_not_accepting_students", $info->show_not_accepting_students))
							value="1"
							data-toggle="show"
							data-toggle-target="#not_accepting_student_options"
						>
						<label class="form-check-label" for="data[{{$info->id}}][data][show_not_accepting_students]">Show that I'm not accepting students</label>
					</div>
					{{-- reset sub-options if main option is unchecked --}}
					<input type="hidden" name="data[{{$info->id}}][data][not_accepting_students]" value="0">
					<input type="hidden" name="data[{{$info->id}}][data][not_accepting_grad_students]" value="0">
					<div
						id="not_accepting_student_options"
						class="border-left ml-3"
						@style([
							'display: none' => !old('data.show_not_accepting_students', $info->show_not_accepting_students)
						])
					>
						<div class="form-group form-check">
							<input
								type="checkbox"
								name="data[{{$info->id}}][data][not_accepting_students]"
								id="data[{{$info->id}}][data][not_accepting_students]"
								@checked(old("data.{$info->id}.data.not_accepting_students", $info->not_accepting_students))
								value="1"
							>
							<label class="form-check-label" for="data[{{$info->id}}][data][not_accepting_students]">Not accepting undergrad students</label>
						</div>
						<div class="form-group form-check">
							<input
								type="checkbox"
								name="data[{{$info->id}}][data][not_accepting_grad_students]"
								id="data[{{$info->id}}][data][not_accepting_grad_students]"
								@checked(old("data.{$info->id}.data.not_accepting_grad_students", $info->not_accepting_grad_students))
								value="1"
							>
							<label class="form-check-label" for="data[{{$info->id}}][data][not_accepting_grad_students]">Not accepting grad students</label>
						</div>
					</div>
				</div>
				<div class="col col-12 col-xl-5">
					<p class="text-muted">This will show a standard note on your profile that you're <em>not</em> currently accepting students of the specified type(s).</p>
				</div>
			</fieldset>
			<fieldset class="form-group row my-3 py-4 border-top border-bottom">
				<div class="col col-12 col-xl-7">
					<div class="form-group form-check p-0">
						<input type="hidden" name="public" value="0">
						<input
							type="checkbox"
							name="public"
							id="public"
							@checked(old('public', $profile->public))
							value="1"
						>
						<label class="form-check-label" for="public">Profile is visible</label>
					</div>
				</div>
				<div class="col col-12 col-xl-5">
					<p class="text-muted">Make profile viewable and searchable by website visitors. If turned off, it will still be accessible to site administrators.</p>
				</div>
			</fieldset>
			{!! Form::submit('Save', array('class' => 'btn btn-primary edit-button')) !!}
			<a href="{{ $profile->url }}" class='btn btn-light edit-button'>Cancel</a>
			{!! Form::close() !!}
		</div>
	@endforeach
</div>
