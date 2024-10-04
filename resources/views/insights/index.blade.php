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

    function getChartData(data, labels)
    {
        let bg_color = ['#56CC9F', '#E0E0E0'];
        const total = data.reduce((sum, value) => sum + value, 0);

        if (total === 0) {
            bg_color = ['#E0E0E0'];
            data = [1];
            labels = [' '];
        }

        return [data, labels, bg_color];
    }

    const toggleTooltipPlugin = {
        id: 'toggleTooltip',
        beforeDraw: function(chart) {

            const total = chart.data.datasets[0].data.reduce((sum, value) => sum + value, 0);

            if (total === 1) {
                chart.options.plugins.tooltip.enabled = false;
            } else {
                chart.options.plugins.tooltip.enabled = true;
            }
        }
    };

    const progressTextPlugin = {
        id: 'progressText',
        afterDraw: function(chart) {
            var {ctx, data} = chart;

            ctx.save();
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            const xCoor = chart.getDatasetMeta(0).data[0].x;
            const yCoor = chart.getDatasetMeta(0).data[0].y;
            var height = chart.height;
            total = data.datasets[0].data.reduce((sum, value) => sum + value, 0);

            if (total === 1) {
                let no_data_text ='No results found \n for the selected filters \n 😭';
                const lines = no_data_text.split('\n');

                ctx.font = '1.15rem Roboto';
                ctx.fillStyle = '#da6f6f';

                lines.forEach((line, index) => {
                    ctx.fillText(line, xCoor, yCoor - (lines.length / 2 - (index + 1) ) * 20);
                });
            }
            else {
                var progress = Number(data.datasets[0].data[0]);
                var remaining = Number(data.datasets[0].data[1]);
                var total = progress + remaining;
                var progress_percentage = total === 0 ? 0 : Math.round((progress / total) * 100);

                var fontSize = (height / 140).toFixed(2);
                ctx.font = fontSize + 'em Roboto';
                ctx.fillStyle = '#198754';

                ctx.fillText(`${progress_percentage}%`, xCoor, yCoor);
            }
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
            const data = chart.data.datasets;
            var no_data_selector = '#'+chart.canvas.id+'.no-data';

            if (data.length === 0 ) {
                document.querySelector(no_data_selector).style.display = 'block';
            }
            else {
                document.querySelector(no_data_selector).style.display = 'none';
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