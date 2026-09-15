<?php

namespace App\Console\Commands;

use App\Models\Project;
use App\Models\QcProductFinalMechanicalDailyCheck;
use App\Models\QcProductFinalMechanicalNcr;
use DateTime;
use DateTimeInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Throwable;

class ImportQcProductFinalMechanicalNcr extends Command
{
    protected $signature = 'qc:import-product-final-mechanical-ncr
        {file : Path file XLSX}
        {--sheet=DETAIL NCR MEKANIK 2026 : Nama sheet Detail NCR}
        {--dry-run : Simulasi tanpa menyimpan ke database}';

    protected $description =
        'Import historical Detail NCR QC Product & Final Mekanik dari XLSX';

    private array $projectMap = [];

    private array $uniqueDailyCheckNcrMap = [];

    public function handle(): int
    {
        error_reporting(
            error_reporting()
            & ~E_DEPRECATED
            & ~E_USER_DEPRECATED
        );

        $path = $this->resolvePath(
            (string) $this->argument('file')
        );

        if (! is_file($path)) {
            $this->error(
                "File tidak ditemukan: {$path}"
            );

            return self::FAILURE;
        }

        $sheetName = trim(
            (string) $this->option('sheet')
        );

        $dryRun = (bool) $this->option(
            'dry-run'
        );

        $this->buildProjectMap();
        $this->buildDailyCheckNcrMap();

        $reader = new Xlsx();

        try {
            $availableSheets =
                $reader->listWorksheetNames(
                    $path
                );
        } catch (Throwable $exception) {
            $this->error(
                'Gagal membaca workbook: '
                . $exception->getMessage()
            );

            return self::FAILURE;
        }

        if (
            ! in_array(
                $sheetName,
                $availableSheets,
                true
            )
        ) {
            $this->error(
                "Sheet tidak ditemukan: {$sheetName}"
            );

            return self::FAILURE;
        }

        $this->info(
            $dryRun
                ? 'MODE DRY-RUN - database tidak akan diubah.'
                : 'MODE IMPORT - data akan disimpan ke database.'
        );

        $this->info(
            "Memproses: {$sheetName}"
        );

        try {
            $result = $this->importSheet(
                $path,
                $sheetName,
                $dryRun
            );
        } catch (Throwable $exception) {
            $this->error(
                'Import gagal: '
                . $exception->getMessage()
            );

            return self::FAILURE;
        }

        $this->newLine();

        $this->table(
            [
                'Inserted',
                'Updated',
                'Skipped',
                'Linked Daily Check',
                'Standalone',
            ],
            [
                [
                    $result['inserted'],
                    $result['updated'],
                    $result['skipped'],
                    $result['linked'],
                    $result['standalone'],
                ],
            ]
        );

        if ($dryRun) {
            $this->warn(
                'Dry-run selesai. Tidak ada perubahan database.'
            );
        } else {
            $this->info(
                'Import Detail NCR Mekanik selesai.'
            );
        }

        return self::SUCCESS;
    }

    private function importSheet(
        string $path,
        string $sheetName,
        bool $dryRun
    ): array {
        $reader = new Xlsx();

        $reader->setReadDataOnly(
            true
        );

        $reader->setLoadSheetsOnly([
            $sheetName,
        ]);

        $spreadsheet =
            $reader->load(
                $path
            );

        $sheet =
            $spreadsheet->getSheetByName(
                $sheetName
            );

        if (! $sheet) {
            throw new \RuntimeException(
                "Sheet tidak ditemukan: {$sheetName}"
            );
        }

        $highestRow =
            $sheet->getHighestDataRow();

        $inserted = 0;
        $updated = 0;
        $skipped = 0;
        $linked = 0;
        $standalone = 0;

        $process = function () use (
            $sheet,
            $sheetName,
            $highestRow,
            $dryRun,
            &$inserted,
            &$updated,
            &$skipped,
            &$linked,
            &$standalone
        ): void {
            /*
             * Header utama berada di row 6.
             * Sub-header kategori berada di row 7.
             * Data dimulai dari row 8.
             */
            for (
                $row = 8;
                $row <= $highestRow;
                $row++
            ) {
                $ncrNumber =
                    $this->nullableString(
                        $sheet
                            ->getCell("B{$row}")
                            ->getFormattedValue()
                    );

                $issuedDate =
                    $this->excelDateValue(
                        $sheet
                            ->getCell("D{$row}")
                            ->getValue()
                    );

                $productName =
                    $this->nullableString(
                        $sheet
                            ->getCell("E{$row}")
                            ->getValue()
                    );

                $description =
                    $this->nullableString(
                        $sheet
                            ->getCell("H{$row}")
                            ->getValue()
                    );

                /*
                 * Row helper / dashboard / kosong tidak dianggap NCR.
                 */
                if (
                    $ncrNumber === null
                    || $issuedDate === null
                    || $productName === null
                    || $description === null
                ) {
                    $skipped++;

                    continue;
                }

                $period =
                    $this->reportingPeriodFromDate(
                        $issuedDate
                    );

                $legacyReference =
                    hash(
                        'sha256',
                        implode(
                            '|',
                            [
                                'qc-product-final-mechanical-ncr',
                                $sheetName,
                                $row,
                            ]
                        )
                    );

                $existing =
                    QcProductFinalMechanicalNcr
                        ::withTrashed()
                        ->where(
                            'legacy_reference',
                            $legacyReference
                        )
                        ->first();

                $projectName =
                    $this->nullableString(
                        $sheet
                            ->getCell("C{$row}")
                            ->getValue()
                    );

                $dailyCheckId =
                    $this->resolveDailyCheckId(
                        $ncrNumber
                    );

                if ($dailyCheckId !== null) {
                    $linked++;
                } else {
                    $standalone++;
                }

                $payload = [
                    'daily_check_id' =>
                        $dailyCheckId,

                    'reporting_year' =>
                        $period['year'],

                    'reporting_month' =>
                        $period['month'],

                    'ncr_number' =>
                        $ncrNumber,

                    'project_id' =>
                        $this->resolveProjectId(
                            $projectName
                        ),

                    'project_name' =>
                        $projectName,

                    'issued_date' =>
                        $issuedDate,

                    'product_name' =>
                        $productName,

                    'nonconformity_location' =>
                        $this->nullableString(
                            $sheet
                                ->getCell("F{$row}")
                                ->getValue()
                        ),

                    'target_unit' =>
                        $this->nullableString(
                            $sheet
                                ->getCell("G{$row}")
                                ->getValue()
                        ),

                    'nonconformity_description' =>
                        $description,

                    'visual_qty' =>
                        $this->integerValue(
                            $sheet
                                ->getCell("I{$row}")
                                ->getValue()
                        ),

                    'dimension_qty' =>
                        $this->integerValue(
                            $sheet
                                ->getCell("J{$row}")
                                ->getValue()
                        ),

                    'function_qty' =>
                        $this->integerValue(
                            $sheet
                                ->getCell("K{$row}")
                                ->getValue()
                        ),

                    'component_status' =>
                        $this->normalizeComponentStatus(
                            $sheet
                                ->getCell("N{$row}")
                                ->getValue()
                        ),

                    'inspector' =>
                        $this->nullableString(
                            $sheet
                                ->getCell("O{$row}")
                                ->getValue()
                        ),

                    'cycle_time_minutes' =>
                        $this->decimalValue(
                            $sheet
                                ->getCell("Q{$row}")
                                ->getValue()
                        ),

                    'source' =>
                        'legacy_xlsx',

                    'legacy_reference' =>
                        $legacyReference,

                    'updated_by' =>
                        null,
                ];

                if ($existing) {
                    $updated++;

                    if ($dryRun) {
                        continue;
                    }

                    if ($existing->trashed()) {
                        $existing->restore();
                    }

                    $existing->fill(
                        $payload
                    );

                    $existing->save();

                    continue;
                }

                $inserted++;

                if ($dryRun) {
                    continue;
                }

                $payload['created_by'] =
                    null;

                QcProductFinalMechanicalNcr
                    ::query()
                    ->create(
                        $payload
                    );
            }
        };

        if ($dryRun) {
            $process();
        } else {
            DB::transaction(
                $process
            );
        }

        $spreadsheet
            ->disconnectWorksheets();

        unset(
            $sheet,
            $spreadsheet,
            $reader
        );

        gc_collect_cycles();

        return [
            'inserted' =>
                $inserted,

            'updated' =>
                $updated,

            'skipped' =>
                $skipped,

            'linked' =>
                $linked,

            'standalone' =>
                $standalone,
        ];
    }

    private function buildProjectMap(): void
    {
        $projects =
            Project::query()
                ->get([
                    'id',
                    'kode_proyek',
                    'nama_proyek',
                ]);

        foreach ($projects as $project) {
            foreach (
                [
                    $project->kode_proyek,
                    $project->nama_proyek,
                ]
                as $value
            ) {
                $key =
                    $this->normalizeKey(
                        $value
                    );

                if (
                    $key !== null
                    && ! isset(
                        $this->projectMap[$key]
                    )
                ) {
                    $this->projectMap[$key] =
                        $project->id;
                }
            }
        }
    }

    private function buildDailyCheckNcrMap(): void
    {
        $grouped = [];

        $dailyChecks =
            QcProductFinalMechanicalDailyCheck::query()
                ->whereNotNull(
                    'ncr_number'
                )
                ->where(
                    'ncr_number',
                    '!=',
                    ''
                )
                ->get([
                    'id',
                    'ncr_number',
                ]);

        foreach ($dailyChecks as $dailyCheck) {
            $key =
                $this->normalizeNcrNumber(
                    $dailyCheck->ncr_number
                );

            if ($key === null) {
                continue;
            }

            $grouped[$key][] =
                $dailyCheck->id;
        }

        foreach ($grouped as $key => $ids) {
            /*
             * Hanya auto-link jika nomor NCR
             * mengarah ke tepat satu Daily Check.
             */
            if (count($ids) === 1) {
                $this->uniqueDailyCheckNcrMap[$key] =
                    $ids[0];
            }
        }
    }

    private function resolveDailyCheckId(
        ?string $ncrNumber
    ): ?int {
        $key =
            $this->normalizeNcrNumber(
                $ncrNumber
            );

        if ($key === null) {
            return null;
        }

        return $this->uniqueDailyCheckNcrMap[$key]
            ?? null;
    }

    private function resolveProjectId(
        ?string $projectName
    ): ?int {
        $key =
            $this->normalizeKey(
                $projectName
            );

        if ($key === null) {
            return null;
        }

        return $this->projectMap[$key]
            ?? null;
    }

    private function reportingPeriodFromDate(
        string $issuedDate
    ): array {
        $date =
            new DateTime(
                $issuedDate
            );

        return [
            'year' =>
                (int) $date->format('Y'),

            'month' =>
                (int) $date->format('n'),
        ];
    }

    private function normalizeComponentStatus(
        mixed $value
    ): string {
        $value =
            mb_strtoupper(
                trim(
                    (string) (
                        $value ?? ''
                    )
                )
            );

        return match ($value) {
            'OK' =>
                QcProductFinalMechanicalNcr
                    ::STATUS_OK,

            'NOK' =>
                QcProductFinalMechanicalNcr
                    ::STATUS_NOK,

            '',
            '-' =>
                QcProductFinalMechanicalNcr
                    ::STATUS_PENDING,

            /*
             * Formula workbook:
             * jika bukan "-" dan bukan "OK"
             * maka status NCR menjadi OPEN.
             */
            default =>
                QcProductFinalMechanicalNcr
                    ::STATUS_NOK,
        };
    }

    private function integerValue(
        mixed $value
    ): int {
        if (
            $value === null
            || $value === ''
            || $value === '-'
        ) {
            return 0;
        }

        if (! is_numeric($value)) {
            return 0;
        }

        return max(
            0,
            (int) round(
                (float) $value
            )
        );
    }

    private function decimalValue(
        mixed $value
    ): ?float {
        if (
            $value === null
            || $value === ''
            || $value === '-'
        ) {
            return null;
        }

        if (! is_numeric($value)) {
            return null;
        }

        return max(
            0,
            (float) $value
        );
    }

    private function excelDateValue(
        mixed $value
    ): ?string {
        if (
            $value === null
            || $value === ''
            || $value === '-'
        ) {
            return null;
        }

        if ($value instanceof DateTimeInterface) {
            return $value->format(
                'Y-m-d'
            );
        }

        if (is_numeric($value)) {
            try {
                return ExcelDate
                    ::excelToDateTimeObject(
                        (float) $value
                    )
                    ->format(
                        'Y-m-d'
                    );
            } catch (Throwable) {
                return null;
            }
        }

        $value =
            trim(
                (string) $value
            );

        foreach (
            [
                'Y-m-d',
                'd/m/Y',
                'd-m-Y',
                'd.m.Y',
            ]
            as $format
        ) {
            $date =
                DateTime::createFromFormat(
                    $format,
                    $value
                );

            if ($date !== false) {
                return $date->format(
                    'Y-m-d'
                );
            }
        }

        $timestamp =
            strtotime(
                $value
            );

        if ($timestamp === false) {
            return null;
        }

        return date(
            'Y-m-d',
            $timestamp
        );
    }

    private function nullableString(
        mixed $value
    ): ?string {
        if (
            $value === null
            || $value === ''
        ) {
            return null;
        }

        $value =
            preg_replace(
                '/\s+/u',
                ' ',
                trim(
                    (string) $value
                )
            );

        if (
            $value === ''
            || $value === '-'
        ) {
            return null;
        }

        return $value;
    }

    private function normalizeNcrNumber(
        mixed $value
    ): ?string {
        $value =
            $this->nullableString(
                $value
            );

        if ($value === null) {
            return null;
        }

        return mb_strtolower(
            preg_replace(
                '/\s+/u',
                '',
                $value
            )
        );
    }

    private function normalizeKey(
        mixed $value
    ): ?string {
        $value =
            $this->nullableString(
                $value
            );

        if ($value === null) {
            return null;
        }

        return mb_strtolower(
            preg_replace(
                '/\s+/u',
                ' ',
                trim($value)
            )
        );
    }

    private function resolvePath(
        string $file
    ): string {
        if (
            str_starts_with(
                $file,
                DIRECTORY_SEPARATOR
            )
        ) {
            return $file;
        }

        return base_path(
            $file
        );
    }
}
