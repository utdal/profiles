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
    <h5>Applications Count by Filing Status</h5>
    <div><canvas id="appCountFilingStatus" x-ref="appCountFilingStatus"></canvas></div>
</div>
