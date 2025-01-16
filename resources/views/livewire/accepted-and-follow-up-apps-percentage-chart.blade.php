<div
    wire:ignore
    x-data="{
        data: @entangle('data'),
        labels: @entangle('labels'),
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
                                        label += ' ' + context.raw; // Add extra spaces here
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
                                        // font: {
                                        //     weight: 'bold',
                                        // },
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
                        this.$refs.acceptedAndFollowUpAppPercentage,
                        {
                        type: 'doughnut',
                        data: {
                            labels: labels,
                            datasets: [{
                                data: data,
                                backgroundColor: bg_color,
                                borderWidth: 3,
                                }],
                        
                        },
                        options: chart_options,
                        plugins: [progressTextPlugin, toggleTooltipPlugin],
                    });

                    Livewire.on('refreshChart5', (data, labels) => {
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
    <h5 class="d-md-flex justify-content-md-center">Applications Accepted & to Follow Up</h5>
    <div class="d-md-flex justify-content-md-center" style="position: relative; height:30vh; width:33.33vw">
        <canvas id="acceptedAndFollowUpAppPercentage" x-ref="acceptedAndFollowUpAppPercentage"></canvas>
    </div>
</div>
