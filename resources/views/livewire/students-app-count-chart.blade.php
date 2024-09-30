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
    <h5>Student Applications Count
        <small class="form-text text-muted d-inline">
            <a role="button" tabindex="0" aria-label="proficiency information" data-toggle="popover" data-trigger="focus" data-popover-content="#chart_info_1"><i class="fas fa-info-circle"></i></a>
        </small>
    </h5>
    <div class="d-flex justify-content-center" style="position: relative; height:40vh; width:60vw">
        <canvas id="appCountBySemester" x-ref="appCountBySemester"></canvas>
    </div>
    <div id="chart_info_1" style="display:none">
        <p><small>Count of single applications submited.</small></p>
    </div>
</div>
