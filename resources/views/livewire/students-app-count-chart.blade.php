
<div>
    <h5>Applications by Semester & School</h5>
    <div><canvas id="appCountBySemester"></canvas></div>
</div>

@push('scripts')
    <script>
        $(function() {

            // Student apps count by semester and school chart
            var ctx = document.getElementById("appCountBySemester").getContext("2d");
            const appCountBySemesterChart = new Chart(
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
            Livewire.on('refreshChart2', (data, labels) => {
                appCountBySemesterChart.data.labels = labels;
                appCountBySemesterChart.data.datasets = data;
                appCountBySemesterChart.update();
            });
        });
    </script>
@endpush
