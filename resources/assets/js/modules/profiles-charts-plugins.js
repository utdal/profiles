    function getChartData(data, labels)
    {
        let bg_color = ['#56CC9F', '#E0E0E0'];
        const total = data.reduce((sum, value) => sum + value, 0);

        if (total === 0) {
            bg_color = ['#E0E0E0'];
            data = [1];
            labels = [' '];
        }

        return [data, labels, bg_color];
    }

    const toggleTooltipPlugin = {
        id: 'toggleTooltip',
        beforeDraw: function(chart) {

            const total = chart.data.datasets[0].data.reduce((sum, value) => sum + value, 0);

            if (total === 1) {
                chart.options.plugins.tooltip.enabled = false;
            } else {
                chart.options.plugins.tooltip.enabled = true;
            }
        }
    };

    const progressTextPlugin = {
        id: 'progressText',
        afterDraw: function(chart) {
            var {ctx, data} = chart;

            ctx.save();
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            const xCoor = chart.getDatasetMeta(0).data[0].x;
            const yCoor = chart.getDatasetMeta(0).data[0].y;
            var height = chart.height;
            total = data.datasets[0].data.reduce((sum, value) => sum + value, 0);

            if (total === 1) {
                let no_data_text ='No results found \n for the selected filters \n 😭';
                const lines = no_data_text.split('\n');

                ctx.font = '1.15rem Roboto';
                ctx.fillStyle = '#da6f6f';

                lines.forEach((line, index) => {
                    ctx.fillText(line, xCoor, yCoor - (lines.length / 2 - (index + 1) ) * 20);
                });
            }
            else {
                var progress = Number(data.datasets[0].data[0]);
                var remaining = Number(data.datasets[0].data[1]);
                var total = progress + remaining;
                var progress_percentage = total === 0 ? 0 : Math.round((progress / total) * 100);

                var fontSize = (height / 140).toFixed(2);
                ctx.font = fontSize + 'em Roboto';
                ctx.fillStyle = '#198754';

                ctx.fillText(`${progress_percentage}%`, xCoor, yCoor);
            }
        }
    };

    const highlightTickPlugin = {
        id: 'highlightTick',
        beforeDraw: (chart) => {
            var ctx = chart.ctx;
            const xAxis = chart.scales['x'];
            const yAxis = chart.scales['y'];

            xAxis.ticks.forEach((tick, index) => {
                const label = xAxis.getLabelForValue(tick.value);

                const xPixel = xAxis.getPixelForValue(tick.value);
                const totalTicks = xAxis.ticks.length;
                const barWidth = xAxis.width / totalTicks;
                const xLeft = xPixel - barWidth / 2;
                const chartHeight = yAxis.bottom - yAxis.top;
                
                if (label === highlightValue) {
                    ctx.fillStyle = '#FEFAEC';
                    ctx.fillRect(xLeft, yAxis.top, barWidth, chartHeight);
                }
                else {
                    ctx.fillStyle = '#FFFFFF';
                    ctx.fillRect(xLeft, yAxis.top, barWidth, chartHeight);
                }
            });
        }
    };

    const validateEmptyDataPlugin = {
        id: 'validateEmptyData',
        afterDraw: function(chart) {
            const data = chart.data.datasets;
            var no_data_selector = '#'+chart.canvas.id+'.no-data';

            if (data.length === 0 ) {
                document.querySelector(no_data_selector).style.display = 'block';
            }
            else {
                document.querySelector(no_data_selector).style.display = 'none';
            }
        }
    };
    export { getChartData, toggleTooltipPlugin,  progressTextPlugin,  highlightTickPlugin, validateEmptyDataPlugin };