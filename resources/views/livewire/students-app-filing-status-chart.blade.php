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
                        animation: {
                            onComplete: function() {
                                Livewire.emit('chartAnimationComplete');
                            }
                        },
                    },
                    plugins: [highlightTickPlugin, validateEmptyDataPlugin],
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
    <h5>Student Applications by Filing Status <small><sup>2</sup></small></h5>

    <div class="d-md-flex mt-3" style="position:relative; justify-content:center;">
        <canvas id="appCountFilingStatus" x-ref="appCountFilingStatus"></canvas>
        <div id="appCountFilingStatus" class="text-overlay no-data" style="display: none;">
            <p>No results found for the selected filters</p>
            <p>😭</p>
        </div>
    </div>
</div>
