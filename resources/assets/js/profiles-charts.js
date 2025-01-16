
import Chart from 'chart.js/auto';
import ChartDataLabels from 'chartjs-plugin-datalabels';
import { getChartData, toggleTooltipPlugin,  progressTextPlugin,  highlightTickPlugin, validateEmptyDataPlugin } from './modules/profiles-charts-plugins.js';

Chart.register(ChartDataLabels);

window.Chart = Chart;
window.getChartData = getChartData;
window.toggleTooltipPlugin = toggleTooltipPlugin;
window.progressTextPlugin = progressTextPlugin;
window.highlightTickPlugin = highlightTickPlugin;
window.validateEmptyDataPlugin = validateEmptyDataPlugin;

window.dispatchEvent(new Event('profiles-charts-module:loaded'));




