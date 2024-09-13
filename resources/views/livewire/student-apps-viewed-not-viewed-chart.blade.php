
<div
    wire:ignore
    x-data="{
        data: @entangle('data'),
        labels: @entangle('labels'),
        selected_semesters: @entangle('selected_semesters'),
        init() {
                var progress = this.data[0];
                var total = Number(this.data[0]) + Number(this.data[1]);
                var progress_percentage = total === 0 ? 0 : Math.round((this.data[0] / total) * 100);

                const progressTextPlugin = {
                    id: 'progressText',
                    afterDraw: function(chart) {
                        var ctx = chart.ctx;
                        var width = chart.width;
                        var height = chart.height;

                        ctx.restore();
                        
                        var fontSize = (height / 140).toFixed(2);
                        ctx.font = fontSize + 'em Roboto';
                        ctx.textBaseline = 'middle';
                        ctx.fillStyle = '#198754';

                        var text = progress_percentage + '%',
                            textX = Math.round((width - ctx.measureText(text).width) / 2),
                            textY = height / 2.15;

                        ctx.fillText(text, textX, textY);
                        ctx.save();
                    }
                };
                // var progress = this.data[0];

                // const progressTextPlugin = {
                //     id: 'progressText',
                //     afterDraw: function(chart) {
                //         var ctx = chart.ctx;
                //         var width = chart.width;
                //         var height = chart.height;

                //         ctx.restore();
                        
                //         var fontSize = (height / 140).toFixed(2);
                //         ctx.font = 'bold ' + fontSize + 'em Roboto';
                //         ctx.textBaseline = 'middle';
                //         ctx.fillStyle = '#198754';

                //         var text = progress,
                //             textX = Math.round((width - ctx.measureText(text).width) / 2),
                //             textY = height / 2.15;

                //         ctx.fillText(text, textX, textY);
                //         ctx.save();
                //     }
                // };
                
                const chart_options = {
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
                            }
                        };

                // Create the new chart instance
                const chart_instance = new Chart(
                    this.$refs.appsPercViewedNotViewed,
                    {
                    type: 'doughnut',
                    data: {
                        labels: this.labels,
                        datasets: [{
                            data: this.data,
                            backgroundColor: ['#4CAF50', '#E0E0E0'],
                            borderWidth: 3
                            }]
                    },
                     options: chart_options,
                     plugins: [progressTextPlugin],
                });

                animateProgress(chart_instance, progress, false);

                Livewire.on('refreshChart4', (data, labels) => {
                    var progress = data[0];

                    if (this.data.every(value => value === 0)) {
                      this.data = [1, 1]; // Fallback to ensure the chart renders
                    }

                    chart_instance.data.labels = labels;
                    chart_instance.data.datasets = [{
                            data: data,
                            backgroundColor: ['#4CAF50', '#E0E0E0'],
                            borderWidth: 3
                            }];
                    chart_instance.data.plugins = [progressTextPlugin];
                    chart_instance.update();
                });
            }
    }"
>
    <h5>Viewed Applications Count</h5>
    <div style="max-width: 400px; max-height: 400px;">
    <canvas id="appsPercViewedNotViewed" x-ref="appsPercViewedNotViewed"></canvas>
    </div>
</div>
