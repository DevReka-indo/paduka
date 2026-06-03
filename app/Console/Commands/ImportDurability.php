<?php

namespace App\Console\Commands;

use App\Models\Durability;
use App\Models\DurabilityKomponen;
use App\Models\DurabilityLokasi;
use App\Models\DurabilityProduk;
use App\Models\DurabilityProyek;
use App\Models\DurabilityTrainset;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportDurability extends Command
{
    protected $signature = 'durability:import
                            {file=storage/app/imports/durability.xlsx : Path file Excel}
                            {--truncate : Kosongkan tabel durability sebelum import}';

    protected $description = 'Import data durability dari file Excel ke database';

    public function handle(): int
    {
        $filePath = base_path($this->argument('file'));

        if (! file_exists($filePath)) {
            $this->error("File tidak ditemukan: {$filePath}");
            return self::FAILURE;
        }

        if ($this->option('truncate')) {
            $this->truncateTables();
        }

        $this->info('Membaca file Excel...');
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();

        $rows = $sheet->toArray(null, true, true, true);

        if (count($rows) < 2) {
            $this->error('File Excel tidak memiliki data.');
            return self::FAILURE;
        }

        $headers = $this->normalizeHeaders($rows[1]);

        $totalImported = 0;
        $totalSkipped = 0;

        DB::beginTransaction();

        try {
            foreach ($rows as $rowNumber => $row) {
                if ($rowNumber === 1) {
                    continue;
                }

                $data = $this->mapRow($headers, $row);

                if ($this->isEmptyRow($data)) {
                    $totalSkipped++;
                    continue;
                }

                $produk = DurabilityProduk::firstOrCreate([
                    'nama_produk' => $data['nama_produk'],
                ]);

                $komponen = DurabilityKomponen::firstOrCreate([
                    'produk_id' => $produk->id,
                    'nama_komponen' => $data['nama_komponen'],
                ]);

                $lokasi = DurabilityLokasi::firstOrCreate([
                    'nama_lokasi' => $data['nama_lokasi'],
                ]);

                $proyek = DurabilityProyek::updateOrCreate(
                    [
                        'nomor_po' => $data['nomor_po'],
                    ],
                    [
                        'customer' => $data['customer'],
                        'nama_proyek' => $data['nama_proyek'],
                    ]
                );

                $trainset = DurabilityTrainset::firstOrCreate([
                    'nomor_trainset' => $data['nomor_trainset'],
                    'tipe_car' => $data['tipe_car'],
                ]);

                Durability::create([
                    'tahun' => $data['tahun'],
                    'proyek_id' => $proyek->id,
                    'komponen_id' => $komponen->id,
                    'trainset_id' => $trainset->id,
                    'lokasi_id' => $lokasi->id,
                    'tgl_kerusakan' => $data['tgl_kerusakan'],
                    'tgl_terbit_lppb' => $data['tgl_terbit_lppb'],
                    'case_keterangan' => $data['case_keterangan'],
                    'rentang_penggantian' => $data['rentang_penggantian'],
                    'jumlah_penggantian' => $data['jumlah_penggantian'],
                ]);

                $totalImported++;
            }

            DB::commit();

            $this->info("Import selesai.");
            $this->info("Berhasil import: {$totalImported} baris.");
            $this->info("Dilewati: {$totalSkipped} baris.");

            return self::SUCCESS;
        } catch (\Throwable $e) {
            DB::rollBack();

            $this->error('Import gagal.');
            $this->error($e->getMessage());

            return self::FAILURE;
        }
    }

    private function normalizeHeaders(array $headerRow): array
    {
        $headers = [];

        foreach ($headerRow as $column => $header) {
            $headers[$column] = strtoupper(trim((string) $header));
        }

        return $headers;
    }

    private function mapRow(array $headers, array $row): array
    {
        return [
            'tahun' => $this->getValue($headers, $row, 'TAHUN'),
            'nomor_po' => $this->getValue($headers, $row, 'NOMOR PO') ?: '-',
            'nama_produk' => $this->getValue($headers, $row, 'NAMA PRODUK'),
            'nama_komponen' => $this->getValue($headers, $row, 'DETAIL KOMPONEN'),
            'nama_lokasi' => $this->getValue($headers, $row, 'LOKASI'),
            'tipe_car' => $this->getValue($headers, $row, 'CAR'),
            'nomor_trainset' => $this->toInteger($this->getValue($headers, $row, 'TRAINSET')),
            'tgl_kerusakan' => $this->getValue($headers, $row, 'TGL KERUSAKAN'),
            'tgl_terbit_lppb' => $this->getValue($headers, $row, 'TGL TERBIT LPPB'),
            'customer' => $this->getValue($headers, $row, 'CUSTOMER'),
            'nama_proyek' => $this->getValue($headers, $row, 'PROYEK'),
            'case_keterangan' => $this->getValue($headers, $row, 'CASE'),
            'rentang_penggantian' => $this->toInteger($this->getValue($headers, $row, 'RENTANG PENGGANTIAN')),
            'jumlah_penggantian' => $this->toInteger($this->getValue($headers, $row, 'JUMLAH PENGGANTIAN')),
        ];
    }

    private function getValue(array $headers, array $row, string $targetHeader): ?string
    {
        foreach ($headers as $column => $header) {
            if ($header === $targetHeader) {
                $value = $row[$column] ?? null;

                if ($value === null) {
                    return null;
                }

                $value = trim((string) $value);

                return $value === '' ? null : $value;
            }
        }

        return null;
    }

    private function toInteger($value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        return (int) preg_replace('/[^0-9\-]/', '', (string) $value);
    }

    private function isEmptyRow(array $data): bool
    {
        return empty($data['nomor_po'])
            && empty($data['nama_produk'])
            && empty($data['nama_komponen'])
            && empty($data['nama_lokasi'])
            && empty($data['nomor_trainset']);
    }

    private function truncateTables(): void
    {
        $this->warn('Mengosongkan tabel durability...');

        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        Durability::truncate();
        DurabilityKomponen::truncate();
        DurabilityProduk::truncate();
        DurabilityLokasi::truncate();
        DurabilityTrainset::truncate();
        DurabilityProyek::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
