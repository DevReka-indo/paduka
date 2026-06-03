@props([
    'chartTrainsetLabels' => [],
    'chartTrainsetValues' => [],
])

<div class="h-[360px]">
    <canvas id="chartLokasiTrainset"></canvas>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof ChartDataLabels !== 'undefined') {
        Chart.register(ChartDataLabels);
    }

    const labels = @json($chartTrainsetLabels ?? []);
    const values = @json($chartTrainsetValues ?? []);

    let lokasiTrainsetChart = null;
    let rebuildTimer = null;

    function getChartTheme() {
        const isDark = document.documentElement.classList.contains('dark');

        return {
            gridColor: isDark ? 'rgba(148, 163, 184, 0.16)' : 'rgba(148, 163, 184, 0.22)',
            tickColor: isDark ? '#94a3b8' : '#64748b',
            labelColor: isDark ? '#f8fafc' : '#0f172a',
            tooltipBg: isDark ? 'rgba(15, 23, 42, 0.96)' : 'rgba(255, 255, 255, 0.98)',
            tooltipTitle: isDark ? '#f8fafc' : '#0f172a',
            tooltipBody: isDark ? '#cbd5e1' : '#334155',
        };
    }

    function destroyChart() {
        if (lokasiTrainsetChart) {
            lokasiTrainsetChart.destroy();
            lokasiTrainsetChart = null;
        }
    }

    function createChart(animated = true) {
        const canvas = document.getElementById('chartLokasiTrainset');

        if (!canvas || typeof Chart === 'undefined') {
            return;
        }

        const theme = getChartTheme();

        destroyChart();

        lokasiTrainsetChart = new Chart(canvas.getContext('2d'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Penggantian',
                    data: values,
                    backgroundColor: 'rgba(37, 99, 235, 0.95)',
                    borderRadius: 8,
                    maxBarThickness: 26,
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                animation: animated ? { duration: 450, easing: 'easeOutCubic' } : false,
                layout: {
                    padding: {
                        right: 48,
                        top: 8,
                        bottom: 0,
                        left: 0
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    datalabels: {
                        anchor: 'end',
                        align: 'right',
                        offset: 6,
                        clamp: true,
                        color: theme.labelColor,
                        font: {
                            size: 11,
                            weight: 'bold'
                        },
                        formatter: function(value) {
                            return Number(value).toLocaleString('id-ID');
                        }
                    },
                    tooltip: {
                        backgroundColor: theme.tooltipBg,
                        titleColor: theme.tooltipTitle,
                        bodyColor: theme.tooltipBody,
                        borderColor: theme.gridColor,
                        borderWidth: 1,
                        padding: 12,
                        callbacks: {
                            label: function(context) {
                                return 'Jumlah: ' + Number(context.raw).toLocaleString('id-ID') + ' Kali';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        suggestedMax: Math.max(...values, 0) * 1.15,
                        title: {
                            display: true,
                            text: 'Jumlah Penggantian (Kali)',
                            color: theme.tickColor,
                            font: {
                                weight: 'bold'
                            }
                        },
                        grid: {
                            color: theme.gridColor
                        },
                        border: {
                            display: false
                        },
                        ticks: {
                            color: theme.tickColor,
                            precision: 0,
                        }
                    },
                    y: {
                        grid: {
                            display: false
                        },
                        border: {
                            display: false
                        },
                        ticks: {
                            color: theme.tickColor,
                            autoSkip: false,
                            font: {
                                size: 11,
                                weight: '600'
                            }
                        }
                    }
                }
            }
        });
    }

    createChart(true);

    window.rebuildLokasiTrainsetChart = function () {
        clearTimeout(rebuildTimer);

        rebuildTimer = setTimeout(function () {
            createChart(false);
        }, 120);
    };

    const observer = new MutationObserver(function (mutations) {
        const classChanged = mutations.some(function (mutation) {
            return mutation.type === 'attributes' && mutation.attributeName === 'class';
        });

        if (!classChanged) {
            return;
        }

        window.rebuildLokasiTrainsetChart();
    });

    observer.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['class'],
    });
});
</script>
@endpush
