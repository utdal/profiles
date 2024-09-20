<div
    wire:ignore
    x-data="{
        data: @entangle('data'),
        labels: @entangle('labels'),
        selected_semesters: @entangle('selected_semesters'),
        init() {
                if (this.data.every(value => value === 0)) {
                      this.data = [1, 1]; // Fallback to ensure the chart renders
                }
                
                var progress = this.data[0];

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
                    animation: {
                        onComplete: function() {
                            Livewire.emit('chartAnimationComplete');
                        }
                    }
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
                     plugins: [progressTextPlugin],
                });

                Livewire.on('refreshChart5', (data, labels) => {
                    if (data.every(value => value === 0)) {
                       var data = [1, 1]; // Fallback to ensure the chart renders
                    }

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
