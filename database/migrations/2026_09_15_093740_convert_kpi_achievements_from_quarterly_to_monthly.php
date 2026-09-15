<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::transaction(function () {
            /*
             * Ambil semua indikator KPI yang masih menggunakan
             * periode quarterly.
             */
            $kpiIndicatorIds = DB::table('work_indicators')
                ->where('type', 'kpi')
                ->where('input_period', 'quarterly')
                ->pluck('id');

            if ($kpiIndicatorIds->isEmpty()) {
                return;
            }

            /*
             * Pindahkan sementara period_number 1-4 ke 21-24.
             *
             * Ini dilakukan untuk menghindari bentrok unique key
             * ketika periode lama dipindahkan ke nomor bulan.
             *
             * 1 -> 21
             * 2 -> 22
             * 3 -> 23
             * 4 -> 24
             */
            DB::table('work_achievements')
                ->whereIn('work_indicator_id', $kpiIndicatorIds)
                ->whereIn('period_number', [1, 2, 3, 4])
                ->update([
                    'period_number' => DB::raw('period_number + 20'),
                ]);

            /*
             * Mapping quarterly lama ke bulan terakhir
             * masing-masing periode.
             *
             * Januari-Maret      -> Maret
             * April-Juni         -> Juni
             * Juli-September     -> September
             * Oktober-Desember   -> Desember
             */
            $periodMap = [
                21 => 3,
                22 => 6,
                23 => 9,
                24 => 12,
            ];

            foreach ($periodMap as $oldPeriod => $newPeriod) {
                DB::table('work_achievements')
                    ->whereIn(
                        'work_indicator_id',
                        $kpiIndicatorIds
                    )
                    ->where(
                        'period_number',
                        $oldPeriod
                    )
                    ->update([
                        'period_number' => $newPeriod,
                    ]);
            }

            /*
             * Setelah seluruh achievement selesai dikonversi,
             * ubah periode input KPI menjadi monthly.
             */
            DB::table('work_indicators')
                ->whereIn('id', $kpiIndicatorIds)
                ->update([
                    'input_period' => 'monthly',
                    'updated_at' => now(),
                ]);
        });
    }

    public function down(): void
    {
        /*
         * Tidak dilakukan rollback otomatis karena setelah sistem
         * berjalan secara bulanan, dapat terdapat data Januari,
         * Februari, April, Mei, dan bulan lainnya yang tidak dapat
         * dikonversi kembali ke quarterly secara aman.
         */
    }
};
