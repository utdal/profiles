<div
    wire:ignore
    x-data="{
        data: @entangle('data'),
        labels: @entangle('labels'),
        init() {

            const appCountBySemesterChart = new Chart(
                this.$refs.appCountBySemester,
                {
                    type: 'bar',
                    data: {
                        labels: this.labels,
                        datasets: this.data.map((dataset, index) => ({
                            ...dataset,
                            backgroundColor: ['#9BD0F5', '#56CC9F', '#FFCFA0', '#FFB1C1'][index],
                        })),    
                    },
                    options: {
                        plugins: {
                            legend: {
                                position: 'bottom'
                            },
                            datalabels: false,
                        },
                        responsive: true,
                        interaction: {
                            intersect: true,
                        },
                        scales: {
                            x: {
                                stacked: false,
                                type: 'category'
                            },
                            y: {
                                stacked: false,
                            },
                        },
                    },
                    plugins: [highlightTickPlugin, validateEmptyDataPlugin],
                }
            );
            Livewire.on('refreshChart2', (data, labels) => {
                appCountBySemesterChart.data.labels = labels;
                appCountBySemesterChart.data.datasets = data;
                appCountBySemesterChart.update();
            });
        }
    }"
>
    <h5 class="d-md-flex justify-content-md-start">Student Applications Count 
        <small class="form-text text-muted d-inline" style="margin-left: 4px; font-size: 0.8em;">
            <a role="button" tabindex="0" aria-label="applications count chart information" data-toggle="class" data-toggle-class="d-md-flex" data-target="#apps_count_chart_info"><i class="fas fa-info-circle"></i></a>
        </small>
    </h5>

    <div class="d-md-flex justify-content-md-center" style="position: relative; height:40vh; width:60vw">
        <canvas id="appCountBySemester" x-ref="appCountBySemester"></canvas>
    </div>
    
    <div id="apps_count_chart_info" class="mt-2 justify-content-md-center" style="text-align: center; display:none;">
        <small class="d-block small text-muted font-italic" style="max-width: 50vw; line-height: 1.2em;"> This chart represents only the count of applications for the semesters selected by the students.</small>
    </div>
</div>
