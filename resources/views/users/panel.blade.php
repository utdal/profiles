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
            <dt>NetID</dt><dd>{{ $user->name }}</dd>
            <dt>First / Last</dt><dd>{{ $user->firstname }} / {{ $user->lastname }}</dd>
            <dt>PEA partial</dt><dd>{{ $user->pea }}</dd>
            <dt>Email</dt><dd>{{ $user->email }}</dd>
          </dl>
        </div>
        <div class="col-sm-4">
          <dl class="">
            <dt>Department</dt><dd>{{ $user->department }}</dd>
            <dt>Title</dt><dd>{{ $user->title }}</dd>
            <dt>School</dt><dd>{{  ($user->school_id) ? $user->school->name : 'none' }}</dd>
          </dl>
        </div>
        <div class="col-sm-3 d-flex flex-column">
          <dl class="">
            <dt>Roles</dt>
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
          <a class="btn btn-secondary btn-sm" href="{{ route('profiles.show', [$user->pea]) }}">Profile</a>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
