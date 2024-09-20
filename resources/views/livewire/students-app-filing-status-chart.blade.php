<div
    wire:ignore
    x-data="{
        data: @entangle('data'),
        labels: @entangle('labels'),
        selected_semesters: @entangle('selected_semesters'),
        init() {

            const appCountFilingStatusChart = new Chart(
                this.$refs.appCountFilingStatus,
                {
                    type: 'bar',
                    data: {
                        labels: this.labels,
                        datasets: this.data,
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
                        animation: {
                            onComplete: function() {
                                Livewire.emit('chartAnimationComplete');
                            }
                        }
                    },
                }
            );
            Livewire.on('refreshChart1', (data, labels) => {
                appCountFilingStatusChart.data.labels = labels;
                appCountFilingStatusChart.data.datasets = data;
                appCountFilingStatusChart.update();
            });
        }
    }"
>
    <h5>Student Applications by Filing Status
        <small class="form-text text-muted d-inline">
            <a role="button" tabindex="0" aria-label="proficiency information" data-toggle="popover" data-trigger="focus" data-popover-content="#chart_info"><i class="fas fa-info-circle"></i></a>
        </small>
    </h5>
    <div class="d-flex" style="position: relative; height:40vh; width:60vw">
        <canvas id="appCountFilingStatus" x-ref="appCountFilingStatus"></canvas>
    </div>
    <div id="chart_info" style="display:none">
        <p><small>A student can choose multiple faculty members in a single application, and each choice is counted separately in the chart.</small></p>
    </div>
</div>
