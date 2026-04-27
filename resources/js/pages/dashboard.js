import Chart from 'chart.js/auto';

function createSalesChart(canvas) {
    return new Chart(canvas, {
        type: 'bar',
        data: {
            labels: ['JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'],
            datasets: [
                {
                    backgroundColor: '#007bff',
                    borderColor: '#007bff',
                    data: [1000, 2000, 3000, 2500, 2700, 2500, 3000],
                },
                {
                    backgroundColor: '#ced4da',
                    borderColor: '#ced4da',
                    data: [700, 1700, 2700, 2000, 1800, 1500, 2000],
                },
            ],
        },
        options: {
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: true,
            },
            plugins: {
                legend: {
                    display: false,
                },
                tooltip: {
                    mode: 'index',
                    intersect: true,
                },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        display: true,
                        lineWidth: 4,
                        color: 'rgba(0, 0, 0, .2)',
                        drawBorder: false,
                    },
                    ticks: {
                        color: '#495057',
                        font: {
                            weight: 'bold',
                        },
                        callback(value) {
                            let tickValue = value;

                            if (tickValue >= 1000) {
                                tickValue /= 1000;
                                tickValue += 'k';
                            }

                            return '$' + tickValue;
                        },
                    },
                },
                x: {
                    grid: {
                        display: false,
                    },
                    ticks: {
                        color: '#495057',
                        font: {
                            weight: 'bold',
                        },
                    },
                },
            },
        },
    });
}

function createVisitorsChart(canvas) {
    return new Chart(canvas, {
        type: 'line',
        data: {
            labels: ['18th', '20th', '22nd', '24th', '26th', '28th', '30th'],
            datasets: [
                {
                    type: 'line',
                    data: [100, 120, 170, 167, 180, 177, 160],
                    backgroundColor: 'transparent',
                    borderColor: '#007bff',
                    pointBorderColor: '#007bff',
                    pointBackgroundColor: '#007bff',
                    fill: false,
                },
                {
                    type: 'line',
                    data: [60, 80, 70, 67, 80, 77, 100],
                    backgroundColor: 'transparent',
                    borderColor: '#ced4da',
                    pointBorderColor: '#ced4da',
                    pointBackgroundColor: '#ced4da',
                    fill: false,
                },
            ],
        },
        options: {
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: true,
            },
            plugins: {
                legend: {
                    display: false,
                },
                tooltip: {
                    mode: 'index',
                    intersect: true,
                },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    suggestedMax: 200,
                    grid: {
                        display: true,
                        lineWidth: 4,
                        color: 'rgba(0, 0, 0, .2)',
                        drawBorder: false,
                    },
                    ticks: {
                        color: '#495057',
                        font: {
                            weight: 'bold',
                        },
                    },
                },
                x: {
                    grid: {
                        display: false,
                    },
                    ticks: {
                        color: '#495057',
                        font: {
                            weight: 'bold',
                        },
                    },
                },
            },
        },
    });
}

function initDashboardCharts() {
    const salesChart = document.getElementById('sales-chart');
    const visitorsChart = document.getElementById('visitors-chart');

    if (salesChart) {
        createSalesChart(salesChart);
    }

    if (visitorsChart) {
        createVisitorsChart(visitorsChart);
    }
}

document.addEventListener('DOMContentLoaded', initDashboardCharts);