<div
    wire:ignore
    x-data="{
        data: @entangle('data'),
        labels: @entangle('labels'),
        current: @entangle('current'),
        selected_semesters: @entangle('selected_semesters'),
        init() {
            // if (this.labels.length === 0 || this.data.length === 0) {
            //     // Set a fallback for each dataset to ensure the chart renders
            //     console.log('in');
            //         this.data = [1]; // Use dummy data to ensure bars appear
            //         this.labels = ['No data']; // Use dummy data to ensure bars appear
            // }
            var current = this.current;

            const currentSemesterPlugin = {
                id: 'currentSemesterPlugin',
                afterDatasetsDraw: function(chart) {
                    const ctx = chart.ctx;
                    const xAxis = chart.scales['x'];  // X-axis
                    const yAxis = chart.scales['y'];  // Y-axis

                    // Determine where to draw the line
                    const currentIndex = chart.data.labels.indexOf('Fall 2024'); // Label of the current period
                    if (currentIndex >= 0) {
                        const xPos = xAxis.getPixelForValue(currentIndex);

                        // Draw the line
                        ctx.save();
                        ctx.beginPath();
                        ctx.moveTo(xPos, yAxis.top);
                        ctx.lineTo(xPos, yAxis.bottom);
                        ctx.lineWidth = 2;
                        ctx.strokeStyle = 'gray';
                        ctx.stroke();

                        // Optional: Add a label for the line
                        ctx.font = '12px Arial';
                        ctx.fillStyle = 'gray';
                        ctx.fillText('Current', xPos + 5, yAxis.top - 5);
                        ctx.restore();
                    }
                }
            };

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
                    plugins: [currentSemesterPlugin],
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
    <div class="d-flex justify-content-center" style="position: relative; height:40vh; width:60vw">
        <canvas id="appCountFilingStatus" x-ref="appCountFilingStatus"></canvas>
    </div>
    <div id="chart_info" style="display:none">
        <p><small>A student can choose multiple faculty members in a single application, and each choice is counted separately in the chart.</small></p>
    </div>
</div>
