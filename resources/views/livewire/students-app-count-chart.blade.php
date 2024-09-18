<div
    wire:ignore
    x-data="{
        data: @entangle('data'),
        labels: @entangle('labels'),
        selected_semesters: @entangle('selected_semesters'),
        init() {

            const appCountBySemesterChart = new Chart(
                this.$refs.appCountBySemester,
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
            Livewire.on('refreshChart2', (data, labels) => {
                appCountBySemesterChart.data.labels = labels;
                appCountBySemesterChart.data.datasets = data;
                appCountBySemesterChart.update();
            });
        }
    }"
>
    <h5>Applications by Semester & School</h5>
    <div><canvas id="appCountBySemester" x-ref="appCountBySemester"></canvas></div>
</div>
