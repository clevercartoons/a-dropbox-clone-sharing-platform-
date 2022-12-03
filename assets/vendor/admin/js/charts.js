(function($) {
    "use strict";

    function maxValuesInCharts(maxValue) {
        if (maxValue >= 5 && maxValue <= 10) {
            return 10 - maxValue + maxValue;
        } else if (maxValue < 5 && maxValue >= 0) {
            return 10 - maxValue + maxValue;
        } else {
            return maxValue;
        }
    }
    const ctxUsers = $('#vironeer-users-charts'),
        ctxEarnings = $('#vironeer-earnings-charts'),
        ctxTransfers = $('#vironeer-transfers-charts');
    const charts = {
        initUsers: function() { this.usersChartsData() },
        initEarnings: function() { this.earningsChartsData() },
        initTransfers: function() { this.transfersChartsData() },
        usersChartsData: function() {
            const dataUrl = BASE_URL + '/dashboard/charts/users';
            const request = $.ajax({
                method: 'GET',
                url: dataUrl
            });
            request.done(function(response) {
                charts.createUsersCharts(response);
            });
        },
        earningsChartsData: function() {
            const dataUrl = BASE_URL + '/dashboard/charts/earnings';
            const request = $.ajax({
                method: 'GET',
                url: dataUrl
            });
            request.done(function(response) {
                charts.createEarningsCharts(response);
            });
        },
        transfersChartsData: function() {
            const dataUrl = BASE_URL + '/dashboard/charts/transfers';
            const request = $.ajax({
                method: 'GET',
                url: dataUrl
            });
            request.done(function(response) {
                charts.createTransfersCharts(response);
            });
        },
        createUsersCharts: function(response) {
            const max = maxValuesInCharts(response.countWeekUsers);
            const labels = response.usersChartLabels;
            const data = response.usersChartData;
            window.Chart && (new Chart(ctxUsers, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Users',
                        data: data,
                        fill: true,
                        tension: 0.3,
                        backgroundColor: PRIMARY_COLOR,
                        borderColor: PRIMARY_COLOR,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false,
                        }
                    },
                    scales: {
                        y: {
                            suggestedMax: max,
                        }
                    }
                }
            })).render();
        },
        createEarningsCharts: function(response) {
            const max = maxValuesInCharts(response.countWeekEarnings);
            const labels = response.earningsChartLabels;
            const data = response.earningsChartData;
            window.Chart && (new Chart(ctxEarnings, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Earnings',
                        data: data,
                        fill: true,
                        tension: 0.3,
                        backgroundColor: SECONDARY_COLOR,
                        borderColor: SECONDARY_COLOR,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false,
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';

                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += WEBSITE_CURRENCY + context.parsed.y;
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            ticks: {
                                beginAtZero: true,
                                callback: function(value, index, values) {
                                    return value + ' ' + WEBSITE_CURRENCY;
                                }
                            },
                            suggestedMax: max,
                        }
                    },
                }
            })).render();
        },
        createTransfersCharts: function(response) {
            const max = maxValuesInCharts(response.countMonthlyTransfers);
            const labels = response.transfersChartLabels;
            const data = response.transfersChartData;
            window.Chart && (new Chart(ctxTransfers, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Transfers',
                        data: data,
                        fill: true,
                        tension: 0.3,
                        backgroundColor: SECONDARY_COLOR,
                        borderColor: SECONDARY_COLOR,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false,
                        }
                    },
                    scales: {
                        y: {
                            suggestedMax: max,
                        }
                    }
                }
            })).render();
        },
    }
    charts.initUsers();
    charts.initEarnings();
    charts.initTransfers();
})(jQuery);