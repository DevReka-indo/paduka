<?php

namespace App\Console\Commands;

use App\Models\QcProductElectricalDailyCheck;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;


class InspectQcProductElectricalDashboard extends Command
{
    protected $signature =
        'qc:inspect-product-electrical {year=2026}';

    protected $description =
        'Inspect agregasi lokasi dan Top 3 temuan QC Product Elektrik';

    public function handle(): int
    {
        $year = (int) $this->argument('year');

        $this->info(
            "QC Product Elektrik - Detail Dashboard {$year}"
        );

        $this->newLine();

        $this->showLocationSummary($year);

        $this->showLocationFormulaComparison(
            $year
        );

        $this->newLine(2);

        $this->showTopFindings(
            $year
        );

        return self::SUCCESS;
    }

    private function baseQuery(
        int $year
    ): Builder {
        return QcProductElectricalDailyCheck::query()
            ->where(
                'source',
                'legacy_xlsx'
            )
            ->whereYear(
                'check_date',
                $year
            );
    }

    private function showLocationSummary(
        int $year
    ): void {
        $this->info(
            'A. LOKASI TEMUAN / INSPECTION GATE'
        );

        /*
         * Pertama kita tampilkan seluruh nilai asli
         * inspection_gate agar terlihat jika ada
         * perbedaan penulisan legacy.
         */
        $rawLocations = $this->baseQuery($year)
            ->selectRaw(
                'inspection_gate, COUNT(*) as total'
            )
            ->groupBy('inspection_gate')
            ->orderByDesc('total')
            ->get();

        $this->line(
            'Nilai Inspection Gate yang ditemukan:'
        );

        $this->table(
            [
                'Inspection Gate',
                'Jumlah Check',
            ],
            $rawLocations
                ->map(fn ($row) => [
                    $row->inspection_gate,
                    (int) $row->total,
                ])
                ->all()
        );

        $this->newLine();

        /*
         * Agregasi per bulan.
         *
         * COUNT(*) digunakan dulu karena grafik
         * Lokasi Temuan legacy tampaknya menghitung
         * jumlah transaksi/check pada tiap lokasi.
         */
        $rows = $this->baseQuery($year)
            ->selectRaw(
                '
                    MONTH(check_date) as month,
                    inspection_gate,
                    COUNT(*) as total
                '
            )
            ->groupByRaw(
                'MONTH(check_date), inspection_gate'
            )
            ->orderByRaw(
                'MONTH(check_date)'
            )
            ->orderBy(
                'inspection_gate'
            )
            ->get();

        $months = $this->monthNames();

        $locations = $rows
            ->pluck('inspection_gate')
            ->filter()
            ->unique()
            ->values();

        $tableRows = [];

        for ($month = 1; $month <= 12; $month++) {
            $monthRows = $rows
                ->where(
                    'month',
                    $month
                );

            if ($monthRows->isEmpty()) {
                continue;
            }

            $row = [
                $months[$month],
            ];

            $rowTotal = 0;

            foreach ($locations as $location) {
                $total = (int) (
                    $monthRows
                        ->firstWhere(
                            'inspection_gate',
                            $location
                        )
                        ?->total ?? 0
                );

                $row[] = $total;

                $rowTotal += $total;
            }

            $row[] = $rowTotal;

            $tableRows[] = $row;
        }

        $headers = array_merge(
            ['Bulan'],
            $locations->all(),
            ['Total']
        );

        $this->table(
            $headers,
            $tableRows
        );
    }

    private function showTopFindings(
        int $year
    ): void {
        $this->info(
            'B. TOP 3 TEMUAN PER KATEGORI'
        );

        $categories = [
            'visual_qty' => 'VISUAL',
            'skun_qty' => 'SKUN',
            'cramping_qty' => 'CRAMPING',
            'marking_qty' => 'MARKING',
            'belltest_qty' => 'BELLTEST',
            'function_qty' => 'FUNCTION',
        ];

        foreach (
            $categories as $column => $label
        ) {
            $this->newLine();

            $this->line(
                "TOP 3 TEMUAN {$label}"
            );

            $this->showTopThreeCategory(
                $year,
                $column
            );
        }
    }

    private function showTopThreeCategory(
        int $year,
        string $column
    ): void {
        $allowedColumns = [
            'visual_qty',
            'skun_qty',
            'cramping_qty',
            'marking_qty',
            'belltest_qty',
            'function_qty',
        ];

        if (
            ! in_array(
                $column,
                $allowedColumns,
                true
            )
        ) {
            throw new \InvalidArgumentException(
                "Kolom temuan tidak valid: {$column}"
            );
        }

        /*
         * Jumlah temuan diakumulasi berdasarkan:
         *
         * bulan + product_name
         *
         * Ini sama dengan konsep dashboard yang sedang
         * kita pakai sekarang: produk dengan jumlah
         * defect terbesar menjadi ranking.
         */
        $rows = $this->baseQuery($year)
            ->where(
                $column,
                '>',
                0
            )
            ->selectRaw(
                "
                    MONTH(check_date) as month,
                    product_name,
                    SUM({$column}) as total
                "
            )
            ->groupByRaw(
                'MONTH(check_date), product_name'
            )
            ->orderByRaw(
                'MONTH(check_date)'
            )
            ->orderByDesc(
                'total'
            )
            ->get();

        $months = $this->monthNames();

        $tableRows = [];

        for ($month = 1; $month <= 12; $month++) {
            $monthRows = $rows
                ->where(
                    'month',
                    $month
                )
                ->sortByDesc(
                    'total'
                )
                ->values()
                ->take(3);

            if ($monthRows->isEmpty()) {
                continue;
            }

            $rank1 = $monthRows->get(0);
            $rank2 = $monthRows->get(1);
            $rank3 = $monthRows->get(2);

            $tableRows[] = [
                $months[$month],

                $rank1?->product_name ?? '-',
                $rank1
                    ? (int) $rank1->total
                    : 0,

                $rank2?->product_name ?? '-',
                $rank2
                    ? (int) $rank2->total
                    : 0,

                $rank3?->product_name ?? '-',
                $rank3
                    ? (int) $rank3->total
                    : 0,
            ];
        }

        if ($tableRows === []) {
            $this->line(
                'Tidak ada temuan.'
            );

            return;
        }

        $this->table(
            [
                'Bulan',
                'Rank 1',
                'Qty',
                'Rank 2',
                'Qty',
                'Rank 3',
                'Qty',
            ],
            $tableRows
        );
    }

    private function monthNames(): array
    {
        return [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];
    }

    private function showLocationFormulaComparison(
        int $year
    ): void {
        $this->newLine(2);

        $this->info(
            'C. PERBANDINGAN FORMULA LOKASI TEMUAN'
        );

        $locations = [
            'Unit Area 1',
            'Unit Area 2',
            'Unit Area 3',
            'Ws. INKA',
        ];

        $months = $this->monthNames();

        $rows = [];

        for ($month = 1; $month <= 12; $month++) {
            $monthQuery = $this->baseQuery($year)
                ->whereMonth(
                    'check_date',
                    $month
                );

            if (
                (clone $monthQuery)->count() === 0
            ) {
                continue;
            }

            foreach ($locations as $location) {
                $query = (clone $monthQuery)
                    ->where(
                        'inspection_gate',
                        $location
                    );

                /*
                * Formula A:
                * semua Daily Check pada lokasi tersebut.
                */
                $checkCount =
                    (clone $query)->count();

                /*
                * Formula B:
                * jumlah Daily Check yang mempunyai
                * minimal satu temuan.
                */
                $rowsWithFinding =
                    (clone $query)
                        ->whereRaw(
                            '(
                                visual_qty
                                + skun_qty
                                + cramping_qty
                                + marking_qty
                                + belltest_qty
                                + function_qty
                            ) > 0'
                        )
                        ->count();

                /*
                * Formula C:
                * total kuantitas seluruh temuan.
                */
                $findingQty =
                    (int) (
                        (clone $query)
                            ->selectRaw(
                                '
                                COALESCE(
                                    SUM(
                                        visual_qty
                                        + skun_qty
                                        + cramping_qty
                                        + marking_qty
                                        + belltest_qty
                                        + function_qty
                                    ),
                                    0
                                ) as total
                                '
                            )
                            ->value('total')
                        ?? 0
                    );

                $rows[] = [
                    $months[$month],
                    $location,
                    $checkCount,
                    $rowsWithFinding,
                    $findingQty,
                ];
            }
        }

        $this->table(
            [
                'Bulan',
                'Lokasi',
                'A: Check',
                'B: Check Ada Temuan',
                'C: Qty Temuan',
            ],
            $rows
        );
    }
}
