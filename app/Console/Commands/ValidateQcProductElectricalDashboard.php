<?php

namespace App\Console\Commands;

use App\Models\QcProductElectricalDailyCheck;
use Illuminate\Console\Command;

class ValidateQcProductElectricalDashboard extends Command
{
    protected $signature =
        'qc:validate-product-electrical {year=2026}';

    protected $description =
        'Validasi agregasi QC Product Elektrik terhadap dashboard legacy';

    public function handle(): int
    {
        $year = (int) $this->argument('year');

        $references = [
            1 => [
                'total_check' => 74,
                'open' => 0,
                'close' => 74,
                'ok' => 15140,
                'nok' => 13,
                'visual' => 51,
                'skun' => 8,
                'cramping' => 32,
                'marking' => 6,
                'belltest' => 0,
                'function' => 0,
            ],

            2 => [
                'total_check' => 93,
                'open' => 0,
                'close' => 93,
                'ok' => 45393,
                'nok' => 993,
                'visual' => 63,
                'skun' => 28,
                'cramping' => 2,
                'marking' => 21,
                'belltest' => 0,
                'function' => 2,
            ],

            3 => [
                'total_check' => 33,
                'open' => 0,
                'close' => 33,
                'ok' => 0,
                'nok' => 0,
                'visual' => 22,
                'skun' => 0,
                'cramping' => 1,
                'marking' => 6,
                'belltest' => 0,
                'function' => 2,
            ],

            4 => [
                'total_check' => 63,
                'open' => 0,
                'close' => 63,
                'ok' => 13937,
                'nok' => 14,
                'visual' => 49,
                'skun' => 7,
                'cramping' => 1,
                'marking' => 32,
                'belltest' => 0,
                'function' => 5,
            ],

            5 => [
                'total_check' => 25,
                'open' => 0,
                'close' => 25,
                'ok' => 0,
                'nok' => 0,
                'visual' => 6,
                'skun' => 22,
                'cramping' => 2,
                'marking' => 9,
                'belltest' => 0,
                'function' => 0,
            ],

            6 => [
                'total_check' => 14,
                'open' => 0,
                'close' => 14,
                'ok' => 651,
                'nok' => 0,
                'visual' => 0,
                'skun' => 1,
                'cramping' => 0,
                'marking' => 3,
                'belltest' => 1,
                'function' => 3,
            ],

            7 => [
                'total_check' => 46,
                'open' => 0,
                'close' => 46,
                'ok' => 26320,
                'nok' => 34,
                'visual' => 66,
                'skun' => 0,
                'cramping' => 0,
                'marking' => 21,
                'belltest' => 0,
                'function' => 25,
            ],
        ];

        $monthNames = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
        ];

        $rows = [];

        foreach (
            $references as $month => $reference
        ) {
            $query =
                QcProductElectricalDailyCheck::query()
                    ->where(
                        'source',
                        'legacy_xlsx'
                    )
                    ->whereYear(
                        'check_date',
                        $year
                    )
                    ->whereMonth(
                        'check_date',
                        $month
                    );

            $actual = [
                'total_check' =>
                    (clone $query)->count(),

                'open' =>
                    (clone $query)
                        ->where('result', 'nok')
                        ->count(),

                'close' =>
                    (clone $query)
                        ->where('result', 'ok')
                        ->count(),

                'ok' =>
                    (int) (clone $query)
                        ->sum('cable_ok_qty'),

                'nok' =>
                    (int) (clone $query)
                        ->sum('cable_nok_qty'),

                'visual' =>
                    (int) (clone $query)
                        ->sum('visual_qty'),

                'skun' =>
                    (int) (clone $query)
                        ->sum('skun_qty'),

                'cramping' =>
                    (int) (clone $query)
                        ->sum('cramping_qty'),

                'marking' =>
                    (int) (clone $query)
                        ->sum('marking_qty'),

                'belltest' =>
                    (int) (clone $query)
                        ->sum('belltest_qty'),

                'function' =>
                    (int) (clone $query)
                        ->sum('function_qty'),
            ];

            foreach (
                $reference as $metric => $expected
            ) {
                $value =
                    $actual[$metric] ?? 0;

                $rows[] = [
                    $monthNames[$month],
                    $metric,
                    $expected,
                    $value,
                    $expected === $value
                        ? 'OK'
                        : 'BEDA',
                    $value - $expected,
                ];
            }
        }

        $this->table(
            [
                'Bulan',
                'Metric',
                'Looker',
                'PADUKA',
                'Status',
                'Selisih',
            ],
            $rows
        );

        $different = collect($rows)
            ->where(4, 'BEDA')
            ->count();

        $this->newLine();

        if ($different === 0) {
            $this->info(
                'Semua agregasi sesuai dashboard legacy.'
            );

            return self::SUCCESS;
        }

        $this->warn(
            "{$different} nilai masih berbeda dengan dashboard legacy."
        );

        return self::SUCCESS;
    }
}
