@props([
    'bulanLabel' => [],
    'dataOpen' => [],
    'dataProses' => [],
    'dataClose' => [],
    'filteredTotalOpen' => 0,
    'filteredTotalProses' => 0,
    'filteredTotalClose' => 0,
])

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    let chartTren = null;
    let chartDonut = null;
    let rebuildTimer = null;

    const labels = @json($bulanLabel);
    const dataOpen = @json($dataOpen);
    const dataProses = @json($dataProses);
    const dataClose = @json($dataClose);

    function getChartTheme() {
        const isDark = document.documentElement.classList.contains('dark');

        return {
            gridColor: isDark ? 'rgba(148, 163, 184, 0.16)' : 'rgba(148, 163, 184, 0.22)',
            tickColor: isDark ? '#94a3b8' : '#64748b',
            borderColor: isDark ? '#111827' : '#ffffff',
            tooltipBg: isDark ? 'rgba(15, 23, 42, 0.96)' : 'rgba(255, 255, 255, 0.98)',
            tooltipTitle: isDark ? '#f8fafc' : '#0f172a',
            tooltipBody: isDark ? '#cbd5e1' : '#334155',
        };
    }

    function destroyCharts() {
        if (chartTren) {
            chartTren.destroy();
            chartTren = null;
        }

        if (chartDonut) {
            chartDonut.destroy();
            chartDonut = null;
        }
    }

    function chartCanvasIsVisible(canvas) {
        return canvas && canvas.offsetParent !== null && canvas.clientWidth > 0 && canvas.clientHeight > 0;
    }

    function createCharts(animated = true) {
        const trenCanvas = document.getElementById('chartTren');
        const donutCanvas = document.getElementById('chartDonut');

        if (!trenCanvas || !donutCanvas || typeof Chart === 'undefined') {
            return;
        }

        if (!chartCanvasIsVisible(trenCanvas) || !chartCanvasIsVisible(donutCanvas)) {
            return;
        }

        const theme = getChartTheme();

        destroyCharts();

        chartTren = new Chart(trenCanvas.getContext('2d'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Open',
                        data: dataOpen,
                        backgroundColor: 'rgba(248, 113, 113, 0.86)',
                        borderRadius: 10,
                        maxBarThickness: 26,
                    },
                    {
                        label: 'Process',
                        data: dataProses,
                        backgroundColor: 'rgba(251, 191, 36, 0.86)',
                        borderRadius: 10,
                        maxBarThickness: 26,
                    },
                    {
                        label: 'Close',
                        data: dataClose,
                        backgroundColor: 'rgba(52, 211, 153, 0.86)',
                        borderRadius: 10,
                        maxBarThickness: 26,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: animated ? {
                    duration: 450,
                    easing: 'easeOutCubic',
                } : false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        backgroundColor: theme.tooltipBg,
                        titleColor: theme.tooltipTitle,
                        bodyColor: theme.tooltipBody,
                        borderColor: theme.gridColor,
                        borderWidth: 1,
                        padding: 12,
                        displayColors: true,
                    },
                },
                scales: {
                    x: {
                        grid: {
                            display: false,
                        },
                        border: {
                            display: false,
                        },
                        ticks: {
                            color: theme.tickColor,
                            font: {
                                size: 11,
                            },
                        },
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: theme.gridColor,
                        },
                        border: {
                            display: false,
                        },
                        ticks: {
                            color: theme.tickColor,
                            precision: 0,
                            font: {
                                size: 11,
                            },
                        },
                    },
                },
            },
        });

        chartDonut = new Chart(donutCanvas.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Open', 'Process', 'Close'],
                datasets: [
                    {
                        data: [
                            {{ $filteredTotalOpen }},
                            {{ $filteredTotalProses }},
                            {{ $filteredTotalClose }}
                        ],
                        backgroundColor: [
                            'rgba(248, 113, 113, 0.9)',
                            'rgba(251, 191, 36, 0.9)',
                            'rgba(52, 211, 153, 0.9)',
                        ],
                        borderColor: theme.borderColor,
                        borderWidth: 4,
                        hoverOffset: 8,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                animation: animated ? {
                    duration: 500,
                    easing: 'easeOutCubic',
                } : false,
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        backgroundColor: theme.tooltipBg,
                        titleColor: theme.tooltipTitle,
                        bodyColor: theme.tooltipBody,
                        borderColor: theme.gridColor,
                        borderWidth: 1,
                        padding: 12,
                        callbacks: {
                            label: function(ctx) {
                                const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                const pct = total > 0 ? Math.round(ctx.raw / total * 100) : 0;

                                return ` ${ctx.label}: ${ctx.raw} (${pct}%)`;
                            },
                        },
                    },
                },
            },
        });
    }

    window.rebuildDashboardCharts = function () {
        clearTimeout(rebuildTimer);

        rebuildTimer = setTimeout(function () {
            createCharts(true);
        }, 120);
    };

    window.addEventListener('dashboard:ncr-tab-active', function () {
        window.rebuildDashboardCharts();
    });

    const observer = new MutationObserver(function (mutations) {
        const classChanged = mutations.some(function (mutation) {
            return mutation.type === 'attributes' && mutation.attributeName === 'class';
        });

        if (!classChanged) {
            return;
        }

        window.rebuildDashboardCharts();
    });

    observer.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['class'],
    });
});
</script>
