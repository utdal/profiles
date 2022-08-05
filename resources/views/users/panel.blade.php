<div class="card user-panel">
  <div class="card-body">
    <div class="container user-body">
      <h4 class="card-title">
        User
        <a href="{{ route('users.show', [$user->pea]) }}" title="link to user">
          {{ $user->display_name }}
        </a>
        <small class="text-muted">
          <em>added:</em> {{ $user->created_at->toFormattedDateString() }},
          <em>updated:</em> {{ $user->updated_at->toFormattedDateString() }},
          <em>last access:</em> {{ $user->last_access ? $user->last_access->format('M j, Y g:i A') : 'never' }}
        </small>
      </h4>
      <hr>
      <div class="row">
        <div class="col-sm-4">
          <dl class="">
            <dt>{{ $settings['account_name'] ?? 'Username' }}</dt><dd>{{ $user->name }}</dd>
            <dt>
              First / Last
              @if($shouldnt_sync_attributes)
                <span class="badge badge-warning">shouldn't sync</span>
              @endif
            </dt>
            <dd>{{ $user->firstname }} / {{ $user->lastname }}</dd>
            <dt>
              URL name
              @if($shouldnt_sync_attributes)
                <span class="badge badge-warning">shouldn't sync</span>
              @endif
            </dt>
            <dd>{{ $user->pea }}</dd>
            <dt>
              Email
              @if($shouldnt_sync_attributes)
                <span class="badge badge-warning">shouldn't sync</span>
              @endif
            </dt>
            <dd>{{ $user->email }}</dd>
          </dl>
        </div>
        <div class="col-sm-4">
          <dl class="">
            <dt>
              Department
              @if($shouldnt_sync_attributes)
                <span class="badge badge-warning">shouldn't sync</span>
              @endif
            </dt>
            <dd>
              {{ $user->department }}
              @if($user->additional_departments)
                / {{ implode(' / ', $user->additional_departments) }}
              @endif
            </dd>
            <dt>
              Title
              @if($shouldnt_sync_attributes)
                <span class="badge badge-warning">shouldn't sync</span>
              @endif
            </dt>
            <dd>{{ $user->title }}</dd>
            <dt>
              School
              @if($shouldnt_sync_school)
                <span class="badge badge-warning">shouldn't sync</span>
              @endif
            </dt>
            <dd>
              {{ ($user->school_id) ? $user->school->short_name : 'none' }}
              @if($additional_schools = $user->additional_schools)
                / {{ implode(' / ', $additional_schools->pluck('short_name')->all()) }}
              @endif
            </dd>
          </dl>
        </div>
        <div class="col-sm-3 d-flex flex-column">
          <dl class="">
            <dt>
              Roles
              @if($shouldnt_sync_roles)
                <span class="badge badge-warning">shouldn't sync</span>
              @endif
            </dt>
            <dd>
              <ul>
                @foreach($user->roles as $role)
                <li>
                  {{ $role->display_name }}
                  @if($role->name === 'school_profiles_editor')
                    <ul>
                    @foreach($user->roleOptions('school_profiles_editor', 'schools') ?? [] as $school_id)
                      @if($school = App\School::find($school_id))
                        <li>{{ $school->short_name }}</li>
                      @endif
                    @endforeach
                    </ul>
                  @endif
                  @if($role->name === 'department_profiles_editor')
                    <ul>
                    @foreach($user->roleOptions('department_profiles_editor', 'departments') ?? [] as $department)
                      <li>{{ $department }}</li>
                    @endforeach
                    </ul>
                  @endif
                </li>
                @endforeach
              </ul>
            </dd>
          </dl>
          @if($user->profiles()->exists())
          <a class="btn btn-secondary btn-sm" href="{{ route('profiles.show', $user->profiles->first()->slug ) }}">Profile</a>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
