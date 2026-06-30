@props([
    'trendLabels' => [],
    'trendValues' => [],
    'topKomponenPenggantian' => collect(),
    'topKomponenDurability' => collect(),
])

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof ChartDataLabels !== 'undefined') {
        Chart.register(ChartDataLabels);
    }

    function shortenLabel(label, maxLength = 18) {
        if (!label) {
            return '-';
        }

        const cleanLabel = String(label).trim();

        if (cleanLabel.length <= maxLength) {
            return cleanLabel;
        }

        return cleanLabel.substring(0, maxLength) + '...';
    }

    const trendLabels = @json($trendLabels ?? []);
    const trendValues = @json($trendValues ?? []);

    const topKomponenPenggantianFullLabels = @json($topKomponenPenggantian->pluck('nama_komponen')->values() ?? []);
    const topKomponenPenggantianLabels = topKomponenPenggantianFullLabels.map(label => shortenLabel(label, 38));
    const topKomponenPenggantianValues = @json($topKomponenPenggantian->pluck('total_penggantian')->map(fn ($value) => (int) $value)->values() ?? []);

    const topKomponenDurabilityFullLabels = @json($topKomponenDurability->pluck('nama_komponen')->values() ?? []);
    const topKomponenDurabilityLabels = topKomponenDurabilityFullLabels.map(label => shortenLabel(label, 18));
    const topKomponenDurabilityValues = @json($topKomponenDurability->pluck('rata_rentang')->map(fn ($value) => round((float) $value))->values() ?? []);

    let trendChart = null;
    let topPenggantianChart = null;
    let topDurabilityChart = null;
    let rebuildTimer = null;

    function getDurabilityChartTheme() {
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

    function destroyDurabilityCharts() {
        if (trendChart) {
            trendChart.destroy();
            trendChart = null;
        }

        if (topPenggantianChart) {
            topPenggantianChart.destroy();
            topPenggantianChart = null;
        }

        if (topDurabilityChart) {
            topDurabilityChart.destroy();
            topDurabilityChart = null;
        }
    }

    function chartCanvasIsVisible(canvas) {
        return canvas && canvas.offsetParent !== null && canvas.clientWidth > 0 && canvas.clientHeight > 0;
    }

    function createDurabilityCharts(animated = true) {
        const theme = getDurabilityChartTheme();

        destroyDurabilityCharts();

        const trendEl = document.getElementById('trendPenggantianChart');
        const topPenggantianEl = document.getElementById('topKomponenPenggantianChart');
        const topDurabilityEl = document.getElementById('topKomponenDurabilityChart');

        if (typeof Chart === 'undefined') {
            return;
        }

        if (trendEl && chartCanvasIsVisible(trendEl)) {
            trendChart = new Chart(trendEl.getContext('2d'), {
                type: 'line',
                data: {
                    labels: trendLabels,
                    datasets: [{
                        label: 'Jumlah Penggantian',
                        data: trendValues,
                        borderColor: 'rgba(37, 99, 235, 1)',
                        backgroundColor: 'rgba(37, 99, 235, 0.12)',
                        borderWidth: 3,
                        pointRadius: 4,
                        pointBackgroundColor: 'rgba(37, 99, 235, 1)',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        tension: 0.35,
                        fill: true,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: animated ? { duration: 450, easing: 'easeOutCubic' } : false,
                    plugins: {
                        datalabels: {
                            display: false
                        },
                        legend: {
                            labels: {
                                color: theme.tickColor
                            }
                        },
                        tooltip: {
                            backgroundColor: theme.tooltipBg,
                            titleColor: theme.tooltipTitle,
                            bodyColor: theme.tooltipBody,
                            borderColor: theme.gridColor,
                            borderWidth: 1,
                            padding: 12,
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
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
                        x: {
                            grid: {
                                color: theme.gridColor
                            },
                            border: {
                                display: false
                            },
                            ticks: {
                                color: theme.tickColor,
                                maxRotation: 35
                            }
                        }
                    }
                }
            });
        }

        if (topPenggantianEl && chartCanvasIsVisible(topPenggantianEl)) {
            topPenggantianChart = new Chart(topPenggantianEl.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: topKomponenPenggantianLabels,
                    datasets: [{
                        label: 'Jumlah Penggantian',
                        data: topKomponenPenggantianValues,
                        backgroundColor: 'rgba(37, 99, 235, 0.9)',
                        borderRadius: 10,
                        maxBarThickness: 28,
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: animated ? { duration: 450, easing: 'easeOutCubic' } : false,
                    layout: {
                        padding: {
                            right: 36
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
                                title: function(context) {
                                    const index = context[0].dataIndex;
                                    return topKomponenPenggantianFullLabels[index] ?? context[0].label;
                                },
                                label: function(context) {
                                    return 'Jumlah: ' + Number(context.raw).toLocaleString('id-ID') + ' Kali';
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            suggestedMax: Math.max(...topKomponenPenggantianValues, 0) * 1.15,
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
                                callback: function(value) {
                                    const label = this.getLabelForValue(value);

                                    if (label.length > 38) {
                                        return label.substring(0, 38) + '...';
                                    }

                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        }

        if (topDurabilityEl && chartCanvasIsVisible(topDurabilityEl)) {
            topDurabilityChart = new Chart(topDurabilityEl.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: topKomponenDurabilityLabels,
                    datasets: [{
                        label: 'Rata-rata Rentang Penggantian',
                        data: topKomponenDurabilityValues,
                        backgroundColor: 'rgba(37, 99, 235, 0.95)',
                        borderRadius: 7,
                        maxBarThickness: 46,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: animated ? { duration: 450, easing: 'easeOutCubic' } : false,
                    layout: {
                        padding: {
                            top: 24,
                            right: 12,
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
                            align: 'top',
                            offset: 4,
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
                                title: function(context) {
                                    const index = context[0].dataIndex;
                                    return topKomponenDurabilityFullLabels[index] ?? context[0].label;
                                },
                                label: function(context) {
                                    return 'Rata-rata: ' + Number(context.raw).toLocaleString('id-ID') + ' Bulan';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            suggestedMax: Math.max(...topKomponenDurabilityValues, 0) * 1.18,
                            grid: {
                                color: theme.gridColor
                            },
                            border: {
                                display: false
                            },
                            ticks: {
                                color: theme.tickColor,
                                padding: 4,
                                precision: 0,
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            border: {
                                display: false
                            },
                            ticks: {
                                color: theme.tickColor,
                                maxRotation: 45,
                                minRotation: 45,
                                padding: 2,
                                font: {
                                    size: 11
                                }
                            }
                        }
                    }
                }
            });
        }
    }

    window.rebuildDurabilityCharts = function () {
        clearTimeout(rebuildTimer);

        rebuildTimer = setTimeout(function () {
            createDurabilityCharts(false);
        }, 120);
    };

    createDurabilityCharts(true);

    const observer = new MutationObserver(function (mutations) {
        const classChanged = mutations.some(function (mutation) {
            return mutation.type === 'attributes' && mutation.attributeName === 'class';
        });

        if (!classChanged) {
            return;
        }

        window.rebuildDurabilityCharts();
    });

    observer.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['class'],
    });
});
</script>
