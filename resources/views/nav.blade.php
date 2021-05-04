<?php

$user = Auth::user();

$user_profile = $user ? $user->profiles()->first() : null;
$user_student_profile = $user ? $user->studentProfiles()->first() : null;
$schools = App\School::where('display_name', '!=', 'Other')->orderBy('short_name')->get();

$can_create_own_profile = $user && $user->can('createOwn', 'App\Profile');
$can_view_profile_admin_index = $user && $user->can('viewAdminIndex', 'App\Profile');
$can_view_school_admin_index = $user && $user->can('viewAdminIndex', 'App\School');
$can_view_user_admin_index = $user && $user->can('viewAdminIndex', 'App\User');
$can_view_tag_admin_index = $user && $user->can('viewAdminIndex', 'Spatie\Tags\Tag');
$can_view_log_admin_index = $user && $user->can('viewAdminIndex', 'App\LogEntry');
$can_update_settings = $user && $user->can('update', 'App\Setting');
$can_create_users = $user && $user->can('create', 'App\User');
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary main-nav">
  <div class="container">
    <a class="navbar-brand" href="{{ url('/') }}" title="{{ $settings['site_title'] ?? 'Profiles' }}">
      @if(isset($settings['logo']))
        <img class="profiles-logo" src="{{ $settings['logo'] ?? asset('img/UTDmono_rev.svg') }}" alt="Logo">
        <span class="vertical-line"></span>
      @endif
      <span class="profiles-wordmark">Profiles</span>
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      @include('_search')
      <ul class="navbar-nav ml-sm-2 mr-sm-auto">
        <li class="nav-item dropdown">
          <a href="#" class="nav-link dropdown-toggle" id="schoolNavDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Schools <span class="caret"></span>
          </a>
          <div class="dropdown-menu" aria-labelledby="schoolNavDropdown">
            @foreach($schools as $school)
            <a class="dropdown-item" href="{{ route('schools.show', $school) }}">
              <span class="fas fa-university fa-fw"></span> {{ $school->display_name }}
            </a>
            @endforeach
          </div>
        </li>
        <li class="nav-item"><a href="{{ route('profiles.index') }}" class="nav-link">Browse All</a></li>
        @if(config('app.enable_students'))
        <li class="nav-item dropdown">
          <a href="#" class="nav-link dropdown-toggle" id="studentNavDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Students <span class="caret"></span>
          </a>
          <div class="dropdown-menu" aria-labelledby="studentNavDropdown">
            <a href="{{ route('students.about') }}" class="dropdown-item"><i class="fas fa-rocket"></i> Get Started with Research</a>
            <a href="{{ route('students.index') }}" class="dropdown-item"><i class="fas fa-users"></i> Student Research Profiles</a>
          </div>
        </li>
        @endif
        @if($can_view_user_admin_index || $can_view_tag_admin_index || $can_create_users || $can_view_profile_admin_index || $can_view_log_admin_index || $can_view_school_admin_index || $can_update_settings)
        <li class="nav-item dropdown">
          <a href="#" class="nav-link dropdown-toggle" id="adminNavDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Admin <span class="caret"></span>
          </a>
          <div class="dropdown-menu" aria-labelledby="adminNavDropdown">
            @if($can_view_profile_admin_index)
            <a class="dropdown-item" href="{{ route('profiles.table') }}">
              <span class="fas fa-users fa-fw"></span> All Profiles
            </a>
            @endif
            @if($can_view_user_admin_index)
            <a class="dropdown-item" href="{{ route('users.index') }}">
              <span class="fas fa-users fa-fw"></span> All Users
            </a>
            @endif
            @if($can_create_users)
            <a class="dropdown-item" href="{{ route('users.create') }}">
              <span class="fas fa-plus fa-fw"></span> Add User / Profile
            </a>
            @endif
            @if($can_view_school_admin_index)
            <a class="dropdown-item" href="{{ route('schools.index') }}">
              <span class="fas fa-university fa-fw"></span> All Schools
            </a>
            @endif
            @if($can_view_tag_admin_index)
            <a class="dropdown-item" href="{{ route('tags.table') }}">
              <span class="fas fa-tags fa-fw"></span> All Tags
            </a>
            @endif
            @if($can_update_settings)
            <a class="dropdown-item" href="{{ route('app.settings.edit') }}">
              <span class="fas fa-cog fa-fw"></span> Site Settings
            </a>
            @endif
            @if($can_view_log_admin_index)
            <a class="dropdown-item" href="{{ route('app.logs.index') }}">
              <span class="fas fa-history fa-fw"></span> Activity Logs
            </a>
            @endif
          </div>
        </li>
        @endif
      </ul>
      <ul class="navbar-nav">
        @if (!$user)
        <li class="nav-item"><a class="nav-link" href="{{ route('login.show') }}">
          <span class="fas fa-sign-in-alt" aria-hidden="true"></span> Login</a>
        </li>
        @else
        <li class="nav-item dropdown">
          <a href="#" class="nav-link dropdown-toggle" id="usernavDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <img class="header_thumb" src="{{ $user_profile ? $user_profile->imageThumbUrl : asset('img/default.png') }}" /> {{ $user->display_name }}
            <span class="caret"></span>
          </a>
          <div class="dropdown-menu" aria-labelledby="usernavDropdown">
            @if($user_profile)
              <a class="dropdown-item" href="{{ route('profiles.show', ['profile' => $user_profile]) }}"><span class="fa fa-user fa-fw"></span> My Profile</a>
            @elseif($can_create_own_profile)
              <a class="dropdown-item" href="{{ route('profiles.create', ['user' => $user]) }}"><span class="fa fa-plus fa-fw"></span> Create Profile</a>
            @endif
            @if($user_student_profile)
              <a class="dropdown-item" href="{{ route('students.show', ['student' => $user_student_profile]) }}"><span class="fa fa-user fa-fw"></span> My Student Research Profile</a>
            @endif
            <a class="dropdown-item" href="{{ route('users.bookmarks.show', ['user' => $user->pea]) }}"><span class="fas fa-bookmark fa-fw"></span> My Bookmarks</a>
            <a class="dropdown-item" href="{{ route('users.show', ['user' => $user->pea]) }}"><span class="fa fa-cog fa-fw"></span> My Account</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="{{ route('logout') }}"><span class="fas fa-sign-out-alt fa-fw" aria-hidden="true"></span> Logout</a>
          </div>
        </li>
          @if(config('app.testing_menu'))
            <li class="nav-item dropdown">
              <a href="#" class="nav-link dropdown-toggle" id="testingNavDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Testing <span class="caret"></span>
              </a>
              <div class="dropdown-menu" aria-labelledby="testingNavDropdown">
                <a class="dropdown-item" href="{{ route('testing.login_as.select') }}"><span class="fas fa-sign-in-alt fa-fw"></span> Log in as User</a>
                <div class="dropdown-divider"></div>
                <h6 class="dropdown-header">Toggle Your Roles:</h6>
                {{--  <div class="dropdown-header">Toggle Your Roles:</div>  --}}
                @foreach(\App\Role::all() as $role)
                  @if($user->hasRole($role->name))
                    <a class="dropdown-item" href="{{ route('testing.roles.remove', ['name' => $role->name]) }}" title="remove role"><i class="fa fa-fw fa-check text-success"></i> {{ $role->name }}</a>
                  @else
                    <a class="dropdown-item" href="{{ route('testing.roles.add', ['name' => $role->name]) }}" title="add role"><i class="fa fa-fw fa-times text-danger"></i> {{ $role->name }}</a>
                  @endif
                @endforeach
              </div>
            </li>
          @endif
        @endif
      </ul>

    </div>
  </div>
</nav>
