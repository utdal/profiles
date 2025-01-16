@extends('layout')
@section('title', 'Profiles Data Update Insights')
@section('header')
	@include('nav')
@stop

@push('scripts')
    <script type="module" src="{{ mix('js/profiles-charts.js') }}"></script>
@endpush

@section('content')
  <div class="container">
  <h1><span class="fa fa-file" aria-hidden="true"></span>Insights</h1>

  @include('errors/list')

  <!-- Nav tabs -->
  <ul class="nav nav-pills" role="tablist">

    <li role="presentation" class="active">
      <a class="nav-link active" href="#student-applications" role="tab" data-toggle="tab">Research Applications</a>
    </li>

    <li role="presentation" style="display:none;">
      <a class="nav-link" href="#profiles" role="tab" data-toggle="tab">Profiles</a>
    </li>
    
    <li role="presentation" style="display:none;">
      <a class="nav-link" href="#admin" role="tab" data-toggle="tab">Admin</a>
    </li>

  </ul>
  
  <hr>
  <!-- Tab panes -->
  <div class="tab-content">
     
    <div role="tabpanel" class="tab-pane active" id="student-applications">
        <div><livewire:insights-filter :semester_options="$semesters_options" :semesters_selected="$semesters_selected" :school_options="$schools_options" :title="$title"/></div>
        <div class="mt-4 mb-5 d-md-flex justify-content-center">
            <div class="col-md-6 d-md-flex justify-content-center"><livewire:accepted-and-follow-up-apps-percentage-chart :selected_semesters="$semesters_selected" :selected_schools="$schools_options"/></div>
            <div class="col-md-6 d-md-flex justify-content-center"><livewire:student-apps-viewed-not-viewed-chart :selected_semesters="$semesters_selected" :selected_schools="$schools_options"/></div>
        </div>
        <div><livewire:students-app-count-chart :selected_semesters="$semesters_selected" :selected_schools="$schools_options"/></div>
        <div><livewire:students-app-filing-status-chart :selected_semesters="$semesters_selected" :selected_schools="$schools_options"/></div>

        <small class="mt-4 small d-block text-muted font-italic">[1] This chart represents only the count of applications for the semesters selected by the students.</small>
        <small class="small d-block text-muted font-italic">[2] This chart represents the count of how faculty have filed student applications for the selected semesters and schools. Given that a student can choose multiple faculty members in a single application, each application can be counted more than once.</small>
        <small class="mt-2 small d-block text-muted font-italic"><span style="color: #198754;">Note: </span> The highlighted stripe corresponds to the current semester.</small>
    </div>
    
    <div role="tabpanel" class="tab-pane" id="profiles">
      @include('insights.profiles-chart')
    </div>
    
    <div role="tabpanel" class="tab-pane" id="admin">
      @include('insights.admin-chart')
    </div>
  
  </div>
@stop

<style>
  .text-overlay {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 1.25rem;
    color: #da6f6f;
    text-align: center;
    z-index: 10;
  }
</style>