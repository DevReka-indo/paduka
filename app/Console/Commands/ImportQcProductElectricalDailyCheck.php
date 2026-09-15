<?php

namespace App\Console\Commands;

use App\Models\Project;
use App\Models\QcProductElectricalDailyCheck;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ImportQcProductElectricalDailyCheck extends Command
{
    protected $signature = 'qc:import-product-electrical
        {file : Path file XLSX}
        {--sheet=* : Nama sheet tertentu yang ingin diimport}
        {--dry-run : Membaca dan memvalidasi tanpa menyimpan ke database}';

    protected $description =
        'Import data historis QC Product Elektrik dari sheet Daily Check Excel';

    private Collection $projectsByCode;

    private Collection $projectsByName;

    private int $inserted = 0;

    private int $updated = 0;

    private int $skipped = 0;

    public function handle(): int
    {
        $path = $this->resolvePath(
            (string) $this->argument('file')
        );

        if (! is_file($path)) {
            $this->error("File tidak ditemukan: {$path}");

            return self::FAILURE;
        }

        $this->loadProjects();

        $this->info('Membaca workbook...');
        $this->line($path);

        $spreadsheet = IOFactory::load($path);

        $requestedSheets = collect(
            $this->option('sheet')
        )
            ->filter()
            ->values();

        $worksheets = collect(
            $spreadsheet->getWorksheetIterator()
        )
            ->filter(function (Worksheet $sheet) use (
                $requestedSheets
            ) {
                if ($requestedSheets->isNotEmpty()) {
                    return $requestedSheets->contains(
                        $sheet->getTitle()
                    );
                }

                return preg_match(
                    '/^Daily Check\s*-\s*.+\s+2026$/i',
                    trim($sheet->getTitle())
                ) === 1;
            })
            ->values();

        if ($worksheets->isEmpty()) {
            $this->error(
                'Tidak ditemukan sheet Daily Check - {BULAN} 2026.'
            );

            return self::FAILURE;
        }

        $this->newLine();

        $this->info(
            'Sheet yang akan diproses:'
        );

        foreach ($worksheets as $sheet) {
            $this->line(
                ' - ' . $sheet->getTitle()
            );
        }

        $this->newLine();

        if ($this->option('dry-run')) {
            foreach ($worksheets as $sheet) {
                $this->importSheet(
                    $sheet,
                    true
                );
            }
        } else {
            DB::transaction(function () use ($worksheets) {
                foreach ($worksheets as $sheet) {
                    $this->importSheet(
                        $sheet,
                        false
                    );
                }
            });
        }

        $this->newLine();

        $this->table(
            ['Status', 'Jumlah'],
            [
                [
                    'Inserted',
                    $this->inserted,
                ],
                [
                    'Updated',
                    $this->updated,
                ],
                [
                    'Skipped',
                    $this->skipped,
                ],
            ]
        );

        if ($this->option('dry-run')) {
            $this->warn(
                'DRY RUN: tidak ada data yang disimpan.'
            );
        } else {
            $this->info(
                'Import QC Product Elektrik selesai.'
            );
        }

        return self::SUCCESS;
    }

    private function importSheet(
        Worksheet $sheet,
        bool $dryRun
    ): void {
        $this->info(
            'Memproses: ' . $sheet->getTitle()
        );

        $headerRow = $this->findHeaderRow($sheet);

        if ($headerRow === null) {
            $this->warn(
                '  Header Daily Check tidak ditemukan. Sheet dilewati.'
            );

            $this->skipped++;

            return;
        }

        $columns = $this->buildColumnMap(
            $sheet,
            $headerRow
        );

        $required = [
            'check_date',
            'product_name',
        ];

        foreach ($required as $field) {
            if (! isset($columns[$field])) {
                $this->warn(
                    "  Kolom wajib {$field} tidak ditemukan."
                );

                $this->skipped++;

                return;
            }
        }

        $highestRow = $sheet->getHighestDataRow();

        $sheetInserted = 0;
        $sheetUpdated = 0;
        $sheetSkipped = 0;

        for (
            $row = $headerRow + 1;
            $row <= $highestRow;
            $row++
        ) {
            $record = $this->readRow(
                $sheet,
                $row,
                $columns
            );

            if ($record === null) {
                $sheetSkipped++;
                $this->skipped++;

                continue;
            }

            if ($dryRun) {
                $sheetInserted++;
                $this->inserted++;

                continue;
            }

            $existing =
                QcProductElectricalDailyCheck::query()
                    ->withTrashed()
                    ->where(
                        'legacy_reference',
                        $record['legacy_reference']
                    )
                    ->first();

            if ($existing) {
                if ($existing->trashed()) {
                    $existing->restore();
                }

                $existing->update($record);

                $sheetUpdated++;
                $this->updated++;

                continue;
            }

            QcProductElectricalDailyCheck::query()
                ->create($record);

            $sheetInserted++;
            $this->inserted++;
        }

        $this->line(
            sprintf(
                '  %s | Insert: %d | Update: %d | Skip: %d',
                $sheet->getTitle(),
                $sheetInserted,
                $sheetUpdated,
                $sheetSkipped
            )
        );
    }

    private function readRow(
        Worksheet $sheet,
        int $row,
        array $columns
    ): ?array {
        $checkDate = $this->dateValue(
            $this->value(
                $sheet,
                $row,
                $columns['check_date'] ?? null
            )
        );

        $checkDate = $this->normalizeCheckDateToSheetPeriod(
            $checkDate,
            $sheet->getTitle()
        );

        $productName = $this->stringValue(
            $this->value(
                $sheet,
                $row,
                $columns['product_name'] ?? null
            )
        );

        /*
         * Baris dianggap bukan transaksi ketika
         * tanggal dan nama produk sama-sama kosong.
         */
        if (
            $checkDate === null
            && $productName === null
        ) {
            return null;
        }

        /*
         * Untuk transaksi historis kita membutuhkan
         * tanggal dan product name.
         */
        if (
            $checkDate === null
            || $productName === null
        ) {
            return null;
        }

        $projectName = $this->stringValue(
            $this->value(
                $sheet,
                $row,
                $columns['project_name'] ?? null
            )
        ) ?? '-';

        $projectId = $this->resolveProjectId(
            $projectName
        );

        $resultRaw = $this->stringValue(
            $this->value(
                $sheet,
                $row,
                $columns['result'] ?? null
            )
        );

        $statusProductRaw = $this->stringValue(
            $this->value(
                $sheet,
                $row,
                $columns['status_product'] ?? null
            )
        );

        $result = $this->resolveResult(
            $resultRaw,
            $statusProductRaw
        );

        $sheetName = trim(
            $sheet->getTitle()
        );

        return [
            'check_date' => $checkDate,

            'project_id' => $projectId,
            'project_name' => $projectName,

            'document_check' =>
                $this->stringValue(
                    $this->value(
                        $sheet,
                        $row,
                        $columns['document_check'] ?? null
                    )
                ) ?? '-',

            'inspection_gate' =>
                $this->stringValue(
                    $this->value(
                        $sheet,
                        $row,
                        $columns['inspection_gate'] ?? null
                    )
                ) ?? '-',

            'product_name' => $productName,

            'check_category' =>
                $this->stringValue(
                    $this->value(
                        $sheet,
                        $row,
                        $columns['check_category'] ?? null
                    )
                ) ?? '-',

            'batch_reference' =>
                $this->stringValue(
                    $this->value(
                        $sheet,
                        $row,
                        $columns['batch_reference'] ?? null
                    )
                ),

            'oil_description' =>
                $this->stringValue(
                    $this->value(
                        $sheet,
                        $row,
                        $columns['oil_description'] ?? null
                    )
                ),

            /*
             * Kategori temuan.
             */
            'visual_qty' =>
                $this->integerValue(
                    $this->value(
                        $sheet,
                        $row,
                        $columns['visual_qty'] ?? null
                    )
                ),

            'skun_qty' =>
                $this->integerValue(
                    $this->value(
                        $sheet,
                        $row,
                        $columns['skun_qty'] ?? null
                    )
                ),

            'cramping_qty' =>
                $this->integerValue(
                    $this->value(
                        $sheet,
                        $row,
                        $columns['cramping_qty'] ?? null
                    )
                ),

            'marking_qty' =>
                $this->integerValue(
                    $this->value(
                        $sheet,
                        $row,
                        $columns['marking_qty'] ?? null
                    )
                ),

            'belltest_qty' =>
                $this->integerValue(
                    $this->value(
                        $sheet,
                        $row,
                        $columns['belltest_qty'] ?? null
                    )
                ),

            'function_qty' =>
                $this->integerValue(
                    $this->value(
                        $sheet,
                        $row,
                        $columns['function_qty'] ?? null
                    )
                ),

            /*
             * Qty produk dan kabel.
             */
            'product_ok_qty' =>
                $this->integerValue(
                    $this->value(
                        $sheet,
                        $row,
                        $columns['product_ok_qty'] ?? null
                    )
                ),

            'product_nok_qty' =>
                $this->integerValue(
                    $this->value(
                        $sheet,
                        $row,
                        $columns['product_nok_qty'] ?? null
                    )
                ),

            'cable_ok_qty' =>
                $this->integerValue(
                    $this->value(
                        $sheet,
                        $row,
                        $columns['cable_ok_qty'] ?? null
                    )
                ),

            'cable_nok_qty' =>
                $this->integerValue(
                    $this->value(
                        $sheet,
                        $row,
                        $columns['cable_nok_qty'] ?? null
                    )
                ),

            'remarks' =>
                $this->stringValue(
                    $this->value(
                        $sheet,
                        $row,
                        $columns['remarks'] ?? null
                    )
                ),

            'closing_oil_date' =>
                $this->dateValue(
                    $this->value(
                        $sheet,
                        $row,
                        $columns['closing_oil_date'] ?? null
                    )
                ),

            'ncr_number' =>
                $this->stringValue(
                    $this->value(
                        $sheet,
                        $row,
                        $columns['ncr_number'] ?? null
                    )
                ),

            /*
             * Daily Check tidak menyediakan kategori NCR
             * secara eksplisit, sehingga belum kita tebak.
             */
            'ncr_category' => null,

            'result' => $result,

            'inspector' =>
                $this->stringValue(
                    $this->value(
                        $sheet,
                        $row,
                        $columns['inspector'] ?? null
                    )
                ) ?? '-',

            'status_is' =>
                $this->stringValue(
                    $this->value(
                        $sheet,
                        $row,
                        $columns['status_is'] ?? null
                    )
                ),

            'cycle_time_minutes' =>
                $this->cycleTimeMinutes(
                    $this->value(
                        $sheet,
                        $row,
                        $columns['cycle_time_minutes'] ?? null
                    )
                ),

            'oil_link' =>
                $this->stringValue(
                    $this->value(
                        $sheet,
                        $row,
                        $columns['oil_link'] ?? null
                    )
                ),

            'source' => 'legacy_xlsx',

            /*
             * Sheet + nomor row membuat command
             * aman dijalankan ulang pada workbook yang sama.
             */
            'legacy_reference' => hash(
                'sha256',
                implode('|', [
                    'qc-product-electrical',
                    $sheetName,
                    $row,
                ])
            ),

            'created_by' => null,
            'updated_by' => null,
        ];
    }

    private function findHeaderRow(
        Worksheet $sheet
    ): ?int {
        $maxRow = min(
            50,
            $sheet->getHighestDataRow()
        );

        $highestColumnIndex =
            Coordinate::columnIndexFromString(
                $sheet->getHighestDataColumn()
            );

        for ($row = 1; $row <= $maxRow; $row++) {
            $headers = [];

            for (
                $column = 1;
                $column <= $highestColumnIndex;
                $column++
            ) {
                $value = $this->stringValue(
                    $this->value(
                        $sheet,
                        $row,
                        $column
                    )
                );

                if ($value !== null) {
                    $headers[] =
                        $this->normalizeHeader($value);
                }
            }

            if (
                in_array('check date', $headers, true)
                && in_array(
                    'product name',
                    $headers,
                    true
                )
            ) {
                return $row;
            }
        }

        return null;
    }

    private function buildColumnMap(
        Worksheet $sheet,
        int $headerRow
    ): array {
        $highestColumnIndex =
            Coordinate::columnIndexFromString(
                $sheet->getHighestDataColumn()
            );

        $map = [];

        for (
            $column = 1;
            $column <= $highestColumnIndex;
            $column++
        ) {
            $header = $this->stringValue(
                $this->value(
                    $sheet,
                    $headerRow,
                    $column
                )
            );

            if ($header === null) {
                continue;
            }

            $field = $this->resolveHeaderField(
                $header
            );

            if ($field !== null) {
                $map[$field] = $column;
            }
        }

        return $map;
    }

    private function resolveHeaderField(
        string $header
    ): ?string {
        $header = $this->normalizeHeader(
            $header
        );

        $aliases = [
            'check date' => 'check_date',
            'tanggal check' => 'check_date',

            'project' => 'project_name',
            'proyek' => 'project_name',

            'doc check' => 'document_check',
            'document check' => 'document_check',

            'inspection gate' => 'inspection_gate',

            'product name' => 'product_name',

            'check category' => 'check_category',

            'oil' => 'oil_description',

            'ts batch car' => 'batch_reference',
            'ts batch' => 'batch_reference',
            'ts batch car no' => 'batch_reference',

            'vt' => 'visual_qty',
            'visual' => 'visual_qty',

            'sk' => 'skun_qty',
            'skun' => 'skun_qty',

            'cr' => 'cramping_qty',
            'cramping' => 'cramping_qty',

            'mk' => 'marking_qty',
            'marking' => 'marking_qty',

            'bt' => 'belltest_qty',
            'belltest' => 'belltest_qty',

            'ft' => 'function_qty',
            'function' => 'function_qty',

            'qty ok produk' => 'product_ok_qty',
            'ok produk' => 'product_ok_qty',
            'ok product' => 'product_ok_qty',

            'qty nok produk' => 'product_nok_qty',
            'nok produk' => 'product_nok_qty',
            'nok product' => 'product_nok_qty',

            'qty ok kabel' => 'cable_ok_qty',
            'ok kabel' => 'cable_ok_qty',

            'qty nok kabel' => 'cable_nok_qty',
            'nok kabel' => 'cable_nok_qty',

            'remarks' => 'remarks',

            'closing oil date' =>
                'closing_oil_date',

            'no ncr' => 'ncr_number',
            'nomor ncr' => 'ncr_number',

            'result' => 'result',

            'inspector' => 'inspector',

            'status is' => 'status_is',

            'status product' =>
                'status_product',

            'cycle time check' =>
                'cycle_time_minutes',

            'link oil' => 'oil_link',
        ];

        return $aliases[$header] ?? null;
    }

    private function value(
        Worksheet $sheet,
        int $row,
        ?int $column
    ): mixed {
        if ($column === null) {
            return null;
        }

        $cell = $sheet->getCell(
            [$column, $row]
        );

        if (
            $cell->getDataType()
            === DataType::TYPE_FORMULA
        ) {
            return $cell->getCalculatedValue();
        }

        return $cell->getValue();
    }

    private function resolveResult(
        ?string $result,
        ?string $statusProduct
    ): string {
        $result = strtoupper(
            trim((string) $result)
        );

        /*
         * NOK harus dicek sebelum OK karena
         * string NOK mengandung "OK".
         */
        if (
            $result !== ''
            && str_contains($result, 'NOK')
        ) {
            return
                QcProductElectricalDailyCheck::RESULT_NOK;
        }

        if (
            $result !== ''
            && str_contains($result, 'OK')
        ) {
            return
                QcProductElectricalDailyCheck::RESULT_OK;
        }

        $statusProduct = strtoupper(
            trim((string) $statusProduct)
        );

        if (
            str_contains(
                $statusProduct,
                'CLOSE'
            )
        ) {
            return
                QcProductElectricalDailyCheck::RESULT_OK;
        }

        if (
            str_contains(
                $statusProduct,
                'OPEN'
            )
        ) {
            return
                QcProductElectricalDailyCheck::RESULT_NOK;
        }

        return
            QcProductElectricalDailyCheck::RESULT_PENDING;
    }

    private function loadProjects(): void
    {
        $projects = Project::query()
            ->get([
                'id',
                'kode_proyek',
                'nama_proyek',
            ]);

        $this->projectsByCode =
            $projects->keyBy(
                fn (Project $project) =>
                    $this->normalizeText(
                        $project->kode_proyek
                    )
            );

        $this->projectsByName =
            $projects->keyBy(
                fn (Project $project) =>
                    $this->normalizeText(
                        $project->nama_proyek
                    )
            );
    }

    private function resolveProjectId(
        string $projectName
    ): ?int {
        $key = $this->normalizeText(
            $projectName
        );

        $project =
            $this->projectsByCode->get($key)
            ?? $this->projectsByName->get($key);

        return $project?->id;
    }

    private function integerValue(
        mixed $value
    ): int {
        if (
            $value === null
            || $value === ''
        ) {
            return 0;
        }

        if (is_numeric($value)) {
            return max(
                0,
                (int) round(
                    (float) $value
                )
            );
        }

        $value = trim(
            (string) $value
        );

        if (
            preg_match(
                '/^\d{1,3}(\.\d{3})+$/',
                $value
            )
        ) {
            $value = str_replace(
                '.',
                '',
                $value
            );
        }

        $value = str_replace(
            [',', ' '],
            '',
            $value
        );

        return is_numeric($value)
            ? max(
                0,
                (int) round(
                    (float) $value
                )
            )
            : 0;
    }

    private function dateValue(
        mixed $value
    ): ?string {
        if (
            $value === null
            || $value === ''
        ) {
            return null;
        }

        /*
        * Jika dari PhpSpreadsheet sudah berupa object tanggal.
        */
        if ($value instanceof \DateTimeInterface) {
            return Carbon::instance($value)
                ->format('Y-m-d');
        }

        /*
        * Excel biasanya menyimpan tanggal sebagai serial number.
        */
        if (is_numeric($value)) {
            $number = (float) $value;

            if ($number <= 0) {
                return null;
            }

            try {
                return Carbon::instance(
                    ExcelDate::excelToDateTimeObject(
                        $number
                    )
                )->format('Y-m-d');
            } catch (\Throwable $exception) {
                return null;
            }
        }

        $value = trim(
            (string) $value
        );

        if ($value === '') {
            return null;
        }

        /*
        * Nilai legacy yang memang bukan tanggal.
        */
        $emptyValues = [
            '-',
            '--',
            'N/A',
            'NA',
            'NULL',
            'NONE',
            'OPEN',
            'BELUM',
            'BELUM CLOSE',
            'BELUM CLOSED',
            'TBD',
        ];

        if (
            in_array(
                strtoupper($value),
                $emptyValues,
                true
            )
        ) {
            return null;
        }

        /*
        * Prioritaskan format Indonesia terlebih dahulu.
        */
        $formats = [
            'd/m/Y',
            'd-m-Y',
            'Y-m-d',
            'd/m/y',
            'd-m-y',
            'd M Y',
            'd M y',
            'd-M-Y',
            'd-M-y',
            'm/d/Y',
        ];

        foreach ($formats as $format) {
            try {
                $date = Carbon::createFromFormat(
                    $format,
                    $value
                );

                if ($date !== false) {
                    return $date->format(
                        'Y-m-d'
                    );
                }
            } catch (\Throwable $exception) {
                /*
                * Coba format berikutnya.
                */
                continue;
            }
        }

        /*
        * Fallback terakhir untuk format tanggal
        * yang masih bisa dikenali Carbon.
        */
        try {
            return Carbon::parse(
                $value
            )->format('Y-m-d');
        } catch (\Throwable $exception) {
            return null;
        }
    }

    private function cycleTimeMinutes(
        mixed $value
    ): ?float {
        if (
            $value === null
            || $value === ''
        ) {
            return null;
        }

        if (is_numeric($value)) {
            $number = (float) $value;

            /*
             * Excel menyimpan time sebagai
             * fraction of day.
             */
            if (
                $number > 0
                && $number < 1
            ) {
                return round(
                    $number * 1440,
                    2
                );
            }

            return round(
                $number,
                2
            );
        }

        $text = trim(
            (string) $value
        );

        if (
            preg_match(
                '/^(\d{1,2}):(\d{2})(?::(\d{2}))?$/',
                $text,
                $matches
            )
        ) {
            $hours = (int) $matches[1];
            $minutes = (int) $matches[2];
            $seconds = isset($matches[3])
                ? (int) $matches[3]
                : 0;

            return round(
                ($hours * 60)
                + $minutes
                + ($seconds / 60),
                2
            );
        }

        return null;
    }

    private function stringValue(
        mixed $value
    ): ?string {
        if ($value === null) {
            return null;
        }

        $value = trim(
            (string) $value
        );

        return $value === ''
            ? null
            : $value;
    }

    private function normalizeHeader(
        string $value
    ): string {
        $value = strtolower(
            trim($value)
        );

        $value = str_replace(
            [
                "\r",
                "\n",
                '.',
                '/',
                '\\',
                '-',
                '_',
                '(',
                ')',
            ],
            ' ',
            $value
        );

        return preg_replace(
            '/\s+/',
            ' ',
            trim($value)
        );
    }

    private function normalizeText(
        ?string $value
    ): string {
        return strtolower(
            preg_replace(
                '/\s+/',
                ' ',
                trim(
                    (string) $value
                )
            )
        );
    }

    private function resolvePath(
        string $path
    ): string {
        if (
            str_starts_with(
                $path,
                DIRECTORY_SEPARATOR
            )
        ) {
            return $path;
        }

        return base_path($path);
    }

    private function normalizeCheckDateToSheetPeriod(
        ?string $date,
        string $sheetName
    ): ?string {
        if ($date === null) {
            return null;
        }

        $months = [
            'JANUARI' => 1,
            'FEBRUARI' => 2,
            'MARET' => 3,
            'APRIL' => 4,
            'MEI' => 5,
            'JUNI' => 6,
            'JULI' => 7,
            'AGUSTUS' => 8,
            'SEPTEMBER' => 9,
            'OKTOBER' => 10,
            'NOVEMBER' => 11,
            'DESEMBER' => 12,
        ];

        if (
            ! preg_match(
                '/Daily Check\s*-\s*([A-Z]+)\s+(\d{4})/i',
                trim($sheetName),
                $matches
            )
        ) {
            return $date;
        }

        $monthName = strtoupper(
            trim($matches[1])
        );

        $expectedYear = (int) $matches[2];

        $expectedMonth =
            $months[$monthName] ?? null;

        if ($expectedMonth === null) {
            return $date;
        }

        $parsedDate = Carbon::parse($date);

        /*
        * Koreksi hanya jika:
        * - bulan transaksi cocok dengan bulan sheet
        * - tahun berbeda tepat 1 tahun
        *
        * Ini mencegah koreksi agresif terhadap data lain.
        */
        if (
            $parsedDate->month === $expectedMonth
            && $parsedDate->year !== $expectedYear
            && abs(
                $parsedDate->year - $expectedYear
            ) === 1
        ) {
            $parsedDate->year(
                $expectedYear
            );
        }

        return $parsedDate->format('Y-m-d');
    }
}
