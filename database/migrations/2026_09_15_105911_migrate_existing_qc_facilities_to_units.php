<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::transaction(function () {
            $facilities = DB::table('qc_facilities')
                ->orderBy('id')
                ->get();

            foreach ($facilities as $facility) {
                /*
                 * Setiap qc_facility existing sebelumnya dianggap
                 * sebagai satu barang fisik.
                 *
                 * Maka dibuat satu unit awal untuk mempertahankan
                 * SN, nomor inventaris, lokasi, dan kondisi existing.
                 */
                $unitId = DB::table('qc_facility_units')
                    ->insertGetId([
                        'qc_facility_id' => $facility->id,

                        'inventory_number' =>
                            $facility->inventory_number,

                        'serial_number' =>
                            $facility->serial_number,

                        'location' =>
                            $facility->location,

                        'condition' =>
                            $facility->condition ?: 'baik',

                        'notes' => null,

                        'created_by' =>
                            $facility->created_by,

                        'updated_by' =>
                            $facility->updated_by,

                        'created_at' =>
                            $facility->created_at ?? now(),

                        'updated_at' =>
                            $facility->updated_at ?? now(),
                    ]);

                /*
                 * Buat histori kalibrasi pertama hanya apabila
                 * data kalibrasi existing memang tersedia.
                 */
                if (
                    $facility->calibration_date !== null
                    || $facility->calibration_valid_until !== null
                ) {
                    DB::table('qc_facility_calibrations')
                        ->insert([
                            'qc_facility_unit_id' =>
                                $unitId,

                            'calibration_date' =>
                                $facility->calibration_date,

                            'calibration_valid_until' =>
                                $facility->calibration_valid_until,

                            'certificate_number' =>
                                null,

                            'calibration_laboratory' =>
                                null,

                            'certificate_path' =>
                                null,

                            'notes' =>
                                null,

                            'created_by' =>
                                $facility->created_by,

                            'updated_by' =>
                                $facility->updated_by,

                            'created_at' =>
                                $facility->created_at ?? now(),

                            'updated_at' =>
                                $facility->updated_at ?? now(),
                        ]);
                }
            }
        });
    }

    public function down(): void
    {
        /*
         * Migration ini hanya menghapus data hasil konversi
         * pada tabel baru.
         *
         * Data lama pada qc_facilities belum dihapus,
         * sehingga rollback masih aman.
         */
        DB::transaction(function () {
            DB::table('qc_facility_calibrations')
                ->delete();

            DB::table('qc_facility_units')
                ->delete();
        });
    }
};
