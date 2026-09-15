<?php

namespace App\Console\Commands;

use App\Models\Project;
use App\Models\QcProductFinalMechanicalDailyCheck;
use DateTime;
use DateTimeInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Throwable;

class ImportQcProductFinalMechanicalDailyCheck extends Command
{
    protected $signature = 'qc:import-product-final-mechanical-daily
        {file : Path file XLSX}
        {--sheet=* : Import hanya sheet tertentu}
        {--dry-run : Simulasi tanpa menyimpan ke database}';

    protected $description =
        'Import historical Daily Check QC Product & Final Mekanik dari XLSX';

    private array $projectMap = [];

    private array $monthMap = [
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

    public function handle(): int
    {
        /*
         * PhpSpreadsheet versi project saat ini dapat menghasilkan
         * deprecation warning pada PHP 8.5. Warning tersebut tidak
         * memengaruhi proses import.
         */
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

        $dryRun = (bool) $this->option(
            'dry-run'
        );

        $this->buildProjectMap();

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

        $requestedSheets =
            $this->option('sheet');

        if ($requestedSheets !== []) {
            $targetSheets =
                collect($requestedSheets)
                    ->filter(
                        fn ($sheet) =>
                            in_array(
                                $sheet,
                                $availableSheets,
                                true
                            )
                    )
                    ->values()
                    ->all();
        } else {
            $targetSheets =
                collect($availableSheets)
                    ->filter(
                        fn ($sheet) =>
                            $this->parseReportingPeriod(
                                $sheet
                            ) !== null
                    )
                    ->values()
                    ->all();
        }

        if ($targetSheets === []) {
            $this->warn(
                'Tidak ada sheet Daily Check 2026 yang ditemukan.'
            );

            return self::SUCCESS;
        }

        $this->info(
            $dryRun
                ? 'MODE DRY-RUN - database tidak akan diubah.'
                : 'MODE IMPORT - data akan disimpan ke database.'
        );

        $this->newLine();

        $totalInserted = 0;
        $totalUpdated = 0;
        $totalSkipped = 0;

        foreach ($targetSheets as $sheetName) {
            $period =
                $this->parseReportingPeriod(
                    $sheetName
                );

            if ($period === null) {
                $this->warn(
                    "Skip sheet tidak dikenal: {$sheetName}"
                );

                continue;
            }

            $this->info(
                "Memproses: {$sheetName}"
            );

            try {
                $result =
                    $this->importSheet(
                        $path,
                        $sheetName,
                        $period['year'],
                        $period['month'],
                        $dryRun
                    );
            } catch (Throwable $exception) {
                $this->error(
                    "Gagal pada sheet {$sheetName}: "
                    . $exception->getMessage()
                );

                return self::FAILURE;
            }

            $totalInserted +=
                $result['inserted'];

            $totalUpdated +=
                $result['updated'];

            $totalSkipped +=
                $result['skipped'];

            $this->table(
                [
                    'Sheet',
                    'Inserted',
                    'Updated',
                    'Skipped',
                ],
                [
                    [
                        $sheetName,
                        $result['inserted'],
                        $result['updated'],
                        $result['skipped'],
                    ],
                ]
            );

            $this->newLine();
        }

        $this->info(
            '=== TOTAL ==='
        );

        $this->table(
            [
                'Inserted',
                'Updated',
                'Skipped',
            ],
            [
                [
                    $totalInserted,
                    $totalUpdated,
                    $totalSkipped,
                ],
            ]
        );

        if ($dryRun) {
            $this->warn(
                'Dry-run selesai. Tidak ada perubahan database.'
            );
        } else {
            $this->info(
                'Import Daily Check Mekanik selesai.'
            );
        }

        return self::SUCCESS;
    }

    private function importSheet(
        string $path,
        string $sheetName,
        int $reportingYear,
        int $reportingMonth,
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

        $inserted = 0;
        $updated = 0;
        $skipped = 0;

        $highestRow =
            $sheet->getHighestDataRow();

        $process = function () use (
            $sheet,
            $sheetName,
            $reportingYear,
            $reportingMonth,
            $highestRow,
            $dryRun,
            &$inserted,
            &$updated,
            &$skipped
        ): void {
            /*
             * Header workbook berada pada row 6.
             * Data dimulai row 7.
             */
            for (
                $row = 7;
                $row <= $highestRow;
                $row++
            ) {
                $checkDate =
                    $this->excelDateValue(
                        $sheet
                            ->getCell("B{$row}")
                            ->getValue()
                    );

                $productName =
                    $this->nullableString(
                        $sheet
                            ->getCell("F{$row}")
                            ->getValue()
                    );

                /*
                 * Summary/helper workbook ikut berada di bawah
                 * data utama. Row dianggap data hanya jika
                 * memiliki Check Date dan Product Name.
                 */
                if (
                    $checkDate === null
                    || $productName === null
                ) {
                    $skipped++;

                    continue;
                }

                $legacyReference =
                    hash(
                        'sha256',
                        implode(
                            '|',
                            [
                                'qc-product-final-mechanical-daily',
                                $sheetName,
                                $row,
                            ]
                        )
                    );

                $existing =
                    QcProductFinalMechanicalDailyCheck
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

                $payload = [
                    'reporting_year' =>
                        $reportingYear,

                    'reporting_month' =>
                        $reportingMonth,

                    'check_date' =>
                        $checkDate,

                    'project_id' =>
                        $this->resolveProjectId(
                            $projectName
                        ),

                    'project_name' =>
                        $projectName,

                    'document_check' =>
                        $this->nullableString(
                            $sheet
                                ->getCell("D{$row}")
                                ->getValue()
                        ),

                    'inspection_gate' =>
                        $this->normalizeInspectionGate(
                            $sheet
                                ->getCell("E{$row}")
                                ->getValue()
                        ),

                    'final_assembly_product_name' =>
                        $productName,

                    'sub_part_assembly_name' =>
                        $this->nullableString(
                            $sheet
                                ->getCell("G{$row}")
                                ->getValue()
                        ),

                    'oil_description' =>
                        $this->nullableString(
                            $sheet
                                ->getCell("H{$row}")
                                ->getValue()
                        ),

                    'batch_reference' =>
                        $this->nullableString(
                            $sheet
                                ->getCell("I{$row}")
                                ->getValue()
                        ),

                    'vt_qty' =>
                        $this->integerValue(
                            $sheet
                                ->getCell("J{$row}")
                                ->getValue()
                        ),

                    'dm_qty' =>
                        $this->integerValue(
                            $sheet
                                ->getCell("K{$row}")
                                ->getValue()
                        ),

                    'wg_qty' =>
                        $this->integerValue(
                            $sheet
                                ->getCell("L{$row}")
                                ->getValue()
                        ),

                    'pt_qty' =>
                        $this->integerValue(
                            $sheet
                                ->getCell("M{$row}")
                                ->getValue()
                        ),

                    'cp_qty' =>
                        $this->integerValue(
                            $sheet
                                ->getCell("N{$row}")
                                ->getValue()
                        ),

                    'ft_qty' =>
                        $this->integerValue(
                            $sheet
                                ->getCell("O{$row}")
                                ->getValue()
                        ),

                    'qty_ok' =>
                        $this->integerValue(
                            $sheet
                                ->getCell("R{$row}")
                                ->getValue()
                        ),

                    'qty_nok' =>
                        $this->integerValue(
                            $sheet
                                ->getCell("S{$row}")
                                ->getValue()
                        ),

                    'remarks' =>
                        $this->nullableString(
                            $sheet
                                ->getCell("U{$row}")
                                ->getValue()
                        ),

                    'closing_oil_date' =>
                        $this->excelDateValue(
                            $sheet
                                ->getCell("V{$row}")
                                ->getValue()
                        ),

                    'ncr_number' =>
                        $this->nullableString(
                            $sheet
                                ->getCell("W{$row}")
                                ->getValue()
                        ),

                    'result' =>
                        $this->normalizeResult(
                            $sheet
                                ->getCell("X{$row}")
                                ->getValue()
                        ),

                    'inspector' =>
                        $this->nullableString(
                            $sheet
                                ->getCell("Y{$row}")
                                ->getValue()
                        ),

                    'status_is' =>
                        $this->normalizeStatusIs(
                            $sheet
                                ->getCell("Z{$row}")
                                ->getValue()
                        ),

                    'cycle_time_minutes' =>
                        $this->decimalValue(
                            $sheet
                                ->getCell("AB{$row}")
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

                QcProductFinalMechanicalDailyCheck
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
        ];
    }

    private function parseReportingPeriod(
        string $sheetName
    ): ?array {
        $matched =
            preg_match(
                '/^\s*Daily Check\s*-\s*([A-Z]+)\s+(2026)\s*$/iu',
                trim($sheetName),
                $matches
            );

        if ($matched !== 1) {
            return null;
        }

        $monthName =
            mb_strtoupper(
                trim(
                    $matches[1]
                )
            );

        if (
            ! isset(
                $this->monthMap[$monthName]
            )
        ) {
            return null;
        }

        return [
            'year' =>
                (int) $matches[2],

            'month' =>
                $this->monthMap[$monthName],
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

    private function normalizeInspectionGate(
        mixed $value
    ): ?string {
        $value =
            $this->nullableString(
                $value
            );

        if ($value === null) {
            return null;
        }

        $key =
            mb_strtoupper(
                preg_replace(
                    '/\s+/u',
                    ' ',
                    trim($value)
                )
            );

        return match ($key) {
            'INCOMING',
            'INCOMING (BY REQUEST)' =>
                'Incoming (by Request)',

            'LASER CUT BENDING',
            'LASER CUT & BENDING',
            'LASER CUT AND BENDING' =>
                'Laser Cut & Bending',

            'WELDING GRINDING',
            'WELDING & GRINDING',
            'WELDING AND GRINDING' =>
                'Welding & Grinding',

            'FINISHING',
            'FINISHING (PAINTING, HL, DLL)' =>
                'Finishing',

            'FINAL TEST',
            'FINAL TEST (TES HUJAN, KURVA, DLL)' =>
                'Final Test',

            default =>
                $value,
        };
    }

    private function normalizeResult(
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
                QcProductFinalMechanicalDailyCheck
                    ::RESULT_OK,

            'NOK' =>
                QcProductFinalMechanicalDailyCheck
                    ::RESULT_NOK,

            default =>
                QcProductFinalMechanicalDailyCheck
                    ::RESULT_PENDING,
        };
    }

    private function normalizeStatusIs(
        mixed $value
    ): ?string {
        $value =
            $this->nullableString(
                $value
            );

        if ($value === null) {
            return null;
        }

        $key =
            mb_strtoupper(
                preg_replace(
                    '/\s+/u',
                    ' ',
                    trim($value)
                )
            );

        return match ($key) {
            'SUDAH ADA' =>
                'Sudah ada',

            'TANPA IS' =>
                'Tanpa IS',

            default =>
                $value,
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

        if (is_numeric($value)) {
            return max(
                0,
                (int) round(
                    (float) $value
                )
            );
        }

        return 0;
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
