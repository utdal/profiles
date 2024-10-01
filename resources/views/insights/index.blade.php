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

    const progressTextPlugin = {
        id: 'progressText',
        afterDraw: function(chart) {
            const {ctx, data} = chart;

            var progress = Number(data.datasets[0].data[0]);
            var remaining = Number(data.datasets[0].data[1]);
            var total = progress + remaining;
            var progress_percentage = total === 0 ? 0 : Math.round((progress / total) * 100);

            ctx.save();
            const xCoor = chart. getDatasetMeta(0).data[0].x;
            const yCoor = chart. getDatasetMeta(0).data[0].y;

            var height = chart.height;
            var fontSize = (height / 140).toFixed(2);
            ctx.font = fontSize + 'em Roboto';
            ctx.fillStyle = '#198754';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.fillText(`${progress_percentage}%`, xCoor, yCoor);
        }
    };

    const highlightTickPlugin = {
        id: 'highlightRegion',
        beforeDraw: (chart) => {
            const ctx = chart.ctx;
            const xAxis = chart.scales['x'];
            const yAxis = chart.scales['y'];

            xAxis.ticks.forEach((tick, index) => {
                const label = xAxis.getLabelForValue(tick.value);

                const xPixel = xAxis.getPixelForValue(tick.value);
                const totalTicks = xAxis.ticks.length;
                const barWidth = xAxis.width / totalTicks;
                const xLeft = xPixel - barWidth / 2;
                const chartHeight = yAxis.bottom - yAxis.top;

                if (label === highlightValue) {
                    ctx.fillStyle = '#FEFAEC';
                    ctx.fillRect(xLeft, yAxis.top, barWidth, chartHeight);
                }
                else {
                    ctx.fillStyle = '#FFFFFF';
                    ctx.fillRect(xLeft, yAxis.top, barWidth, chartHeight);
                }
            });
        }
    };

    const validateEmptyDataPlugin = {
        id: 'validateEmptyData',
        afterDraw: function(chart) {
          
            const ctx = chart.ctx;
            const { chartArea } = chart;
            const chartType = chart.config.type;
            let empty = false;

            if (chartType === 'doughnut') {
              const data = chart.data.datasets[0].data;
              if (data.every(value => value === 0)) {
                var img = new Image();
                img.src = img_route_doughnut;
                empty = true;
              }
            }
            else if (chartType === 'bar') {
              const data = chart.data.datasets;
              if (data.length === 0 ) {
                var img = new Image();
                img.src = img_route_bar;
                empty = true;
              }
            }
            if (empty) {
              img.alt = 'No results found for the selected filters';
              img.onload = function() {
                  const imgX = chartArea.left + (chartArea.width / 2) - (img.width / 2);
                  const imgY = chartArea.top + (chartArea.height / 2) - (img.height / 2);
                  ctx.drawImage(img, imgX, imgY);
              }
            }
        }
    };

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
  <div class="tab-content">
     
    <div role="tabpanel" class="tab-pane active container" id="student-applications">
        <div><livewire:insights-filter :semester_options="$semesters_options" :semesters_selected="$semesters_selected" :school_options="$schools_options" :title="$title"/></div>
        <div class="flex-row mt-4 d-md-flex justify-content-center">
            <div><livewire:accepted-and-follow-up-apps-percentage-chart :selected_semesters="$semesters_selected" :selected_schools="$schools_options"/></div>
            <div><livewire:student-apps-viewed-not-viewed-chart :selected_semesters="$semesters_selected" :selected_schools="$schools_options"/></div>
        </div>
        <div class="mt-4 d-md-flex justify-content-md-center"><livewire:students-app-count-chart :selected_semesters="$semesters_selected" :selected_schools="$schools_options"/></div>
        <div class="mt-4 d-md-flex justify-content-md-center"><livewire:students-app-filing-status-chart :selected_semesters="$semesters_selected" :selected_schools="$schools_options"/></div>

        <div class="mt-5 d-md-flex justify-content-md-start">
            <small class="small text-muted font-italic mt-2 " style="max-width: 50vw;"><span style="color: #198754;">Note: </span> The highlighted stripe in green corresponds to the current semester.</small>
        </div>
    </div>
    
    <div role="tabpanel" class="tab-pane" id="profiles">
      @include('insights.profiles-chart')
    </div>
    
    <div role="tabpanel" class="tab-pane" id="admin">
      @include('insights.admin-chart')
    </div>
  
  </div>
@stop

