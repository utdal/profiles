{!! Form::open(['url' => route('profiles.index'), 'method' => 'get', 'class' => 'form-inline my-2 my-lg-0 mr-sm-2']) !!}
  <div class="search input-group input-group-lg">
    <input class="search form-control" type="search" name="search" placeholder="search..." aria-label="Search" value="{{ $search ?? '' }}">
    <div class="input-group-append">
      <button class="btn btn-search" type="submit" data-toggle="replace-icon" data-newicon="fas fa-sync fa-spin" data-inputrequired="nav input[type=search]">
        <i class="fas fa-search"></i><span class="sr-only">search</span>
      </button>
    </div>
  </div>
{!! Form::close() !!}
