@props([
    'chartLabels' => [],
    'chartLabelsFull' => [],
    'chartValues' => [],
    'chartColors' => [],
])

<div class="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
    <div class="flex flex-col gap-4 border-b border-gray-100 px-6 py-5 dark:border-gray-800 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h2 class="text-base font-bold text-gray-900 dark:text-white">
                Rata-rata Durability Semua Komponen
            </h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Satuan: Hari. Semakin tinggi semakin baik.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-4 text-xs font-semibold text-gray-500 dark:text-gray-400">
            <span class="inline-flex items-center gap-2">
                <span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                Durability Tinggi (&gt; 90 hari)
            </span>

            <span class="inline-flex items-center gap-2">
                <span class="h-2.5 w-2.5 rounded-full bg-amber-500"></span>
                Durability Sedang (31 - 90 hari)
            </span>

            <span class="inline-flex items-center gap-2">
                <span class="h-2.5 w-2.5 rounded-full bg-red-500"></span>
                Durability Rendah (&le; 30 hari)
            </span>
        </div>
    </div>

    <div class="p-6">
        <div class="h-[620px]">
            <canvas id="chartDurabilityKomponen"></canvas>
        </div>

        <div class="mt-5 flex items-start gap-2 rounded-2xl border border-blue-100 bg-blue-50 px-4 py-3 text-sm text-blue-700 dark:border-blue-900/40 dark:bg-blue-900/20 dark:text-blue-300">
            <i class="fa-solid fa-circle-info mt-0.5"></i>
            <p>
                Durability dihitung berdasarkan rata-rata rentang penggantian komponen dalam satuan hari.
            </p>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof ChartDataLabels !== 'undefined') {
        Chart.register(ChartDataLabels);
    }

    const labels = @json($chartLabels);
    const fullLabels = @json($chartLabelsFull);
    const values = @json($chartValues);
    const colors = @json($chartColors);

    let durabilityKomponenChart = null;
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
        if (durabilityKomponenChart) {
            durabilityKomponenChart.destroy();
            durabilityKomponenChart = null;
        }
    }

    function createChart(animated = true) {
        const canvas = document.getElementById('chartDurabilityKomponen');

        if (!canvas || typeof Chart === 'undefined') {
            return;
        }

        const theme = getChartTheme();

        destroyChart();

        durabilityKomponenChart = new Chart(canvas.getContext('2d'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Rata-rata Durability',
                    data: values,
                    backgroundColor: colors,
                    borderRadius: 8,
                    maxBarThickness: 18,
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                animation: animated ? { duration: 450, easing: 'easeOutCubic' } : false,
                layout: {
                    padding: {
                        right: 70,
                        left: 0,
                        top: 4,
                        bottom: 0,
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    datalabels: {
                        anchor: 'end',
                        align: 'right',
                        offset: 8,
                        clamp: true,
                        color: theme.labelColor,
                        font: {
                            size: 12,
                            weight: 'bold'
                        },
                        formatter: function(value) {
                            return Number(value).toLocaleString('id-ID') + ' hari';
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
                                return fullLabels[index] ?? context[0].label;
                            },
                            label: function(context) {
                                const value = Number(context.raw).toLocaleString('id-ID');

                                let kategori = 'Rendah';
                                if (context.raw > 90) {
                                    kategori = 'Tinggi';
                                } else if (context.raw >= 31) {
                                    kategori = 'Sedang';
                                }

                                return 'Rata-rata: ' + value + ' hari (' + kategori + ')';
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
                            text: 'Rata-rata Durability (Hari)',
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
                            precision: 0
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
                                size: 11
                            }
                        }
                    }
                }
            }
        });
    }

    createChart(true);

    window.rebuildDurabilityKomponenChart = function () {
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

        window.rebuildDurabilityKomponenChart();
    });

    observer.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['class'],
    });
});
</script>
@endpush
