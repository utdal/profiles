@extends('layout')
@section('title', 'Profiles Data Update Insights')
@section('header')
	@include('nav')
@stop


@once
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
        <script> Chart.register(ChartDataLabels); </script>
    @endpush
@endonce

@push('scripts')
    <script>
        // Function to animate the progress
        function animateProgress(chart_instance, progress, percentage) {
          var start = 0;
          var end = progress;
          var current = start;
          var increment = Math.round(end / 100);

          function step() {
              current += increment;
              if (current >= end) current = end;
              chart_instance.data.datasets[0].data[0] = current;
              chart_instance.data.datasets[0].data[1] = percentage ? 100 - current : chart_instance.data.datasets[0].data[1];
              chart_instance.update();

              if (current < end) {
                  requestAnimationFrame(step);
              }
          }
          step();
        }
    </script>
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
  <div class="tab-content coi-reports-export">
     
    <div role="tabpanel" class="tab-pane active container" id="student-applications">
      <div><livewire:insights-filter :semester_options="$semesters_options" :school_options="$schools_options" :title="$title"/></div>
      <div class="row mt-4 d-flex justify-content-center align-items-center">
        <div class="col-md-6 d-flex justify-content-center"><livewire:accepted-and-follow-up-apps-percentage-chart :selected_semesters="$semesters_options" :selected_schools="$schools_options"/></div>
        <div class="col-md-6 d-flex justify-content-center"><livewire:student-apps-viewed-not-viewed-chart :selected_semesters="$semesters_options" :selected_schools="$schools_options"/></div>
      </div>
      <div class="mt-4"><livewire:students-app-count-chart :selected_semesters="$semesters_options" :selected_schools="$schools_options"/></div>
      <div class="mt-4"><livewire:students-app-filing-status-chart :selected_semesters="$semesters_options" :selected_schools="$schools_options"/></div>
    </div>
    
    <div role="tabpanel" class="tab-pane" id="profiles">
      @include('insights.profiles-chart')
    </div>
    
    <div role="tabpanel" class="tab-pane" id="admin">
      @include('insights.admin-chart')
    </div>
  
  </div>
@stop

