<div
    wire:ignore
    x-data="{
        data: @entangle('data'),
        labels: @entangle('labels'),
        current: @entangle('current'),
        selected_semesters: @entangle('selected_semesters'),
        init() {
            var current = this.current;

            const currentSemesterPlugin = {
                id: 'currentSemesterPlugin',
                afterDatasetsDraw: function(chart) {
                    const ctx = chart.ctx;
                    const xAxis = chart.scales['x'];  // X-axis
                    const yAxis = chart.scales['y'];  // Y-axis

                    // Determine where to draw the line
                    const currentIndex = chart.data.labels.indexOf(current); // Label of the current period
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
            Livewire.on('refreshChart2', (data, labels) => {
                appCountBySemesterChart.data.labels = labels;
                appCountBySemesterChart.data.datasets = data;
                appCountBySemesterChart.update();
            });
        }
    }"
>
    <h5>Student Applications Count
        <small class="form-text text-muted d-inline">
            <a role="button" tabindex="0" aria-label="proficiency information" data-toggle="popover" data-trigger="focus" data-popover-content="#chart_info_1"><i class="fas fa-info-circle"></i></a>
        </small>
    </h5>
    <div class="d-flex justify-content-center" style="position: relative; height:40vh; width:60vw">
        <canvas id="appCountBySemester" x-ref="appCountBySemester"></canvas>
    </div>
    <div id="chart_info_1" style="display:none">
        <p><small>Count of single applications submited.</small></p>
    </div>
</div>
