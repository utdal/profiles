<div
    wire:ignore
    x-data="{
        data: @entangle('data'),
        labels: @entangle('labels'),
        selected_semesters: @entangle('selected_semesters'),
        init() {
            window.addEventListener('profiles-charts-module:loaded', () => {
                var progress = this.data[0];
                var [data, labels, bg_color] = getChartData(this.data, this.labels);

                const chart_options = {
                            responsive: true,
                            cutout: '35%',
                            rotation: -90,
                            circumference: 360,
                            plugins: {
                                tooltip: {
                                    enabled: true,
                                    callbacks: {
                                        label: function(context) {
                                            let label = context.dataset.label || '';

                                            if (label) {
                                                label += ': ';
                                            }
                                            label += ' ' + context.raw;
                                            return label;
                                        }
                                    }
                                },
                                legend: {
                                    position: 'bottom',
                                },
                                
                                datalabels: {
                                    labels: {
                                        value: {
                                            color: ['white', 'gray'],
                                        },
                                    }
                                },
                            },
                            animation: {
                                onComplete: function() {
                                    Livewire.emit('chartAnimationComplete');
                                }
                            },
                        };

                // Create the new chart instance
                const chart_instance = new Chart(
                    this.$refs.appsPercViewedNotViewed,
                    {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: data,
                            backgroundColor: bg_color,
                            borderWidth: 3
                            }]
                    },
                     options: chart_options,
                     plugins: [progressTextPlugin, toggleTooltipPlugin],
                });

                Livewire.on('refreshChart4', (data, labels) => {
                    var [data, labels, bg_color] = getChartData(data, labels);
                    chart_instance.data.labels = labels;
                    chart_instance.data.datasets = [{
                            data: data,
                            backgroundColor: bg_color,
                            borderWidth: 3
                            }];
                    chart_instance.update();
                });
            });
        }
    }"
>
    <h5 class="d-md-flex justify-content-md-center">Applications Viewed</h5>
    <div class="d-md-flex justify-content-md-center" style="position: relative; height:30vh; width:33.33vw">
        <canvas id="appsPercViewedNotViewed" x-ref="appsPercViewedNotViewed"></canvas>
    </div>
</div>
