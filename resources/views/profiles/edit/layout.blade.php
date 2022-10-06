<h1>Edit <a href="{{ action('ProfilesController@show', [$profile->slug]) }}">{{ $profile->name }}</a>'s
    @yield('section_name', ucfirst($section))</h1>

@yield('info')
@yield('academics_analytics_publications_modal')

{!! Form::open(['url' => route('profiles.update', [$profile->slug, $section]), 'files' => $files ?? false]) !!}

@include('profiles.edit._insert_button', ['type' => 'prepend'])
<div class="row mb-4 lower-border"></div>

<div class="sortable" data-next-row-id="-1">

    @yield('form')

</div>

@include('profiles.edit._insert_button', ['type' => 'append'])
@include('profiles.edit._buttons')

{!! Form::close() !!}
