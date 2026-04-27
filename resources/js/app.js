import './bootstrap';
import * as bootstrap from 'bootstrap';
import './adminlte-widgets';
import './helpers';
import './toast';
import './pages/dashboard';
import Alpine from 'alpinejs';
import DataTable from 'datatables.net-bs5';
import Chart from 'chart.js/auto';

window.bootstrap = bootstrap;
window.Chart = Chart;
window.Alpine = Alpine;
Alpine.start();

window.DataTable = DataTable;
