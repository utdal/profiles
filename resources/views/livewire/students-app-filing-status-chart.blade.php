
<div>
    <h5>Student Research Applications Filing Status Count</h5>
    <div><canvas id="appCountFilingStatus"></canvas></div>
</div>

@push('scripts')
    <script>
        $(function() {

            // Student apps count by semester and school chart
            var ctx = document.getElementById("appCountFilingStatus").getContext("2d");
            const appCountFilingStatusChart = new Chart(
                ctx,
                {
                    type: 'bar',
                    data: {
                        labels: @json($labels),
                        datasets: @json($data),
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
        });
    </script>
@endpush
