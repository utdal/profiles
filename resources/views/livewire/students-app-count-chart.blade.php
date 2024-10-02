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
                        animation: {
                            onComplete: function() {
                                Livewire.emit('chartAnimationComplete');
                            }
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
    <h5>Student Applications Count <small><sup>1</sup></small></h5>
    <div class="d-md-flex mt-3" style="position:relative; justify-content:center;">
        <canvas id="appCountBySemester" x-ref="appCountBySemester"></canvas>
        <div id="appCountBySemester" class="text-overlay no-data" style="display: none;">
            <p>No results found for the selected filters</p>
            <p>😭</p>
        </div>
    </div>
</div>

