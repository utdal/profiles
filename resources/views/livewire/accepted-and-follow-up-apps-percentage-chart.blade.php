<div
    wire:ignore
    x-data="{
        data: @entangle('data'),
        labels: @entangle('labels'),
        selected_semesters: @entangle('selected_semesters'),
        init() {
                var progress = this.data[0];

                const chart_options = {
                        responsive: true,
                        cutout: '35%',
                        rotation: -90,
                        circumference: 360,
                        plugins: {
                            tooltip: {
                                enabled: true,
                            },
                            legend: {
                                position: 'bottom',
                            },
                            datalabels: {
                                labels: {
                                    value: {
                                        // font: {
                                        //     weight: 'bold',
                                        // },
                                        color: ['white', 'gray'],
                                    },
                                }
                            }
                        },
                };

                // Create the new chart instance
                const chart_instance = new Chart(
                    this.$refs.acceptedAndFollowUpAppPercentage,
                    {
                    type: 'doughnut',
                    data: {
                        labels: this.labels,
                        datasets: [{
                            data: this.data,
                            backgroundColor: ['#4CAF50', '#E0E0E0'],
                            borderWidth: 3,
                            }],
                       
                    },
                     options: chart_options,
                     plugins: [progressTextPlugin, validateEmptyDataPlugin],
                });

                Livewire.on('refreshChart5', (data, labels) => {
                    chart_instance.data.labels = labels;
                    chart_instance.data.datasets = [{
                            data: data,
                            backgroundColor: ['#4CAF50', '#E0E0E0'],
                            borderWidth: 3
                            }];
                    chart_instance.update();
                });
            }
    }"
>
    <h5 class="d-flex justify-content-center">Applications Accepted & to Follow Up</h5>
    <div class="d-flex justify-content-center" style="position: relative; height:30vh; width:33.33vw">
        <canvas id="acceptedAndFollowUpAppPercentage" x-ref="acceptedAndFollowUpAppPercentage"></canvas>
    </div>
</div>
