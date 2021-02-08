<h1>Edit <a href="{{ action('ProfilesController@show', [$profile->slug]) }}">{{$profile->name}}</a>'s Contact Information</h1>
<div class="row">
	@foreach($data as $info)
		<div class="col col-md-4">
			{!! Form::open(['url' => route('profiles.update-image', [$profile->slug]), 'method' => 'POST', 'files' => true]) !!}
			<label for="file">Icon</label>
			<img id="file-img" class="profile_photo" src="{{ $profile->imageUrl }}" />
			<br />
			<br />
			<div class="control-group">
				<div class="controls">
					{!! Form::file('image', ['id' => 'file', 'name' => 'image', 'required' => 'true', 'accept' => 'image/*', 'class' => 'd-none form-control']) !!}
					<label for="file" class="btn btn-secondary btn-block"><i class="fas fa-plus"></i> Select Image</label>
					@foreach($errors->get('image') as $image_error)
						@include('alert', ['message' => $image_error, 'type' => 'danger'])
						<p class="d-block invalid-feedback"><i class="fas fa-asterisk"></i> {!! $image_error !!}</p>
					@endforeach
				</div>
			</div>
			<button type="submit" class="btn btn-primary btn-block" data-toggle="replace-icon" data-newicon="sync" data-newiconclasses="fa-spin" data-inputrequired="#file">
				<i class="fas fa-upload"></i> Replace Image
			</button>
			{!! Form::close() !!}
			<br>
			<br>
			{!! Form::open(['url' => route('profiles.update-banner', [$profile->slug]), 'method' => 'POST', 'files' => true]) !!}
			<label for="banner">Banner</label>
			<img id="banner-img" class="profile_photo" src="{{ $profile->banner_url }}" />
			<br />
			<br />
			<div class="control-group">
				<div class="controls">
					{!! Form::file('banner_image', ['id' => 'banner', 'name' => 'banner_image', 'required' => 'true', 'accept' => 'image/*', 'class' => 'd-none form-control']) !!}
					<label for="banner" class="btn btn-secondary btn-block"><i class="fas fa-plus"></i> Select Image</label>
					@foreach($errors->get('banner_image') as $banner_image_error)
						@include('alert', ['message' => $banner_image_error, 'type' => 'danger'])
						<p class="d-block invalid-feedback"><i class="fas fa-asterisk"></i> {!! $banner_image_error !!}</p>
					@endforeach
				</div>
			</div>
			<button type="submit" class="btn btn-primary btn-block" data-toggle="replace-icon" data-newicon="sync" data-newiconclasses="fa-spin" data-inputrequired="#banner">
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
				<input type="text" class="form-control" name="data[{{$info->id}}][data][title]" id="data[{{$info->id}}][data][title]" value="{{$info->title}}"  />
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
			<div class="row">
					<div class="form-group col col-sm-6 col-12">
						<label for="data[{{$info->id}}][data][email]">Email</label>
						<input type="email" class="form-control" name="data[{{$info->id}}][data][email]" id="data[{{$info->id}}][data][email]" value="{{$info->email}}"  />
					</div>
					<div class="form-group col col-sm-6 col-12">
						<label for="data[{{$info->id}}][phone][tertiary_title]">Phone</label>
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
					<label for="data[{{$info->id}}][data][url_name]">Secondary URL</label>
					<input type="url" class="form-control" name="data[{{$info->id}}][data][secondary_url]" id="data[{{$info->id}}][data][secondary_url]" value="{{$info->secondary_url}}"  />
				</div>
				<div class="form-group col col-sm-6 col-12">
					<label for="data[{{$info->id}}][data][url_name]">Secondary URL Title</label>
					<input type="text" class="form-control" name="data[{{$info->id}}][data][secondary_url_name]" id="data[{{$info->id}}][data][secondary_url_name]" value="{{$info->secondary_url_name}}"  />
				</div>
			</div>
			<div class="row">
				<div class="form-group col col-sm-6 col-12">
					<label for="data[{{$info->id}}][data][url_name]">Tertiary URL</label>
					<input type="url" class="form-control" name="data[{{$info->id}}][data][tertiary_url]" id="data[{{$info->id}}][data][tertiary_url]" value="{{$info->tertiary_url}}"  />
				</div>
				<div class="form-group col col-sm-6 col-12">
					<label for="data[{{$info->id}}][data][url_name]">Tertiary URL Title</label>
					<input type="text" class="form-control" name="data[{{$info->id}}][data][tertiary_url_name]" id="data[{{$info->id}}][data][tertiary_url_name]" value="{{$info->tertiary_url_name}}"  />
				</div>
			</div>
			<div class="form-group">
				<label for="data[{{$info->id}}][data][orc_id]">ORCID</label>
				<input type="text" class="form-control" name="data[{{$info->id}}][data][orc_id]" id="data[{{$info->id}}][data][orc_id]" value="{{$info->orc_id}}"  onkeyup="javascript:$(this).val($(this).val().replace('https://orcid.org/', '').replace('http://orcid.org/', ''));" pattern="^[0-9a-zA-Z]{4}-[0-9a-zA-Z]{4}-[0-9a-zA-Z]{4}-[0-9a-zA-Z]{4}$"/>
			</div>
			<div class="form-group row">
				<div class="col col-4">
					<label for="data[{{$info->id}}][data][orc_id_managed]">Auto Update Publications</label><br>
					<label class="switch">
					  <input type="hidden" name="data[{{$info->id}}][data][orc_id_managed]" id="data[{{$info->id}}][data][orc_id_managed]" value="0">
					  <input type="checkbox" name="data[{{$info->id}}][data][orc_id_managed]" id="data[{{$info->id}}][data][orc_id_managed]" value="1" @if($info->orc_id_managed) checked @endif>
					  <span class="slider round"></span>
					</label>
				</div>
				<div class="col col-8">
					<br>
					<p>Refresh all publications via ORCID. All previous publications will be removed and fresh data will be pulled in at regular intervals.</p>
				</div>
			</div>
			<div class="form-group row">
				<div class="col col-4">
					<label for="visibility">Fancy Header</label><br>
					<label class="switch pull-left">
						<input type="hidden" name="data[{{$info->id}}][data][fancy_header]" id="data[{{$info->id}}][data][fancy_header]" value="0">
						<input type="checkbox" name="data[{{$info->id}}][data][fancy_header]" id="data[{{$info->id}}][data][fancy_header]" value="1" @if($info->fancy_header) checked @endif>
						<span class="slider round"></span>
					</label>
				</div>
				<div class="col col-8">
					<br>
					<p>This will use a full-width header style - please make sure uploaded image is of sufficient quality!</p>
					<label for="visibility">Align Header Right</label><br>
					<label class="switch pull-left">
						<input type="hidden" name="data[{{$info->id}}][data][fancy_header_right]" id="data[{{$info->id}}][data][fancy_header_right]" value="0">
						<input type="checkbox" name="data[{{$info->id}}][data][fancy_header_right]" id="data[{{$info->id}}][data][fancy_header_right]" value="1" @if($info->fancy_header_right) checked @endif>
						<span class="slider round"></span>
					</label>
				</div>
			</div>
			<div class="form-group row">
				<div class="col col-4">
					<label for="visibility">Profile Visibility</label><br>
					<label class="switch pull-left">
						<input type="hidden" name="public" id="public" value="0">
						<input type="checkbox" name="public" id="public" value="1" @if($profile->public) checked @endif>
						<span class="slider round"></span>
					</label>
				</div>
				<div class="col col-8">
					<br>
					<p>Hide profile from public index. It will still be accessible via the public API and to administration.</p>
				</div>
			</div>
			{!! Form::submit('Save', array('class' => 'btn btn-primary edit-button')) !!}
			<a href="{{ $profile->url }}" class='btn btn-light edit-button'>Cancel</a>
			{!! Form::close() !!}
		</div>
	@endforeach
</div>
