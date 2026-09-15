<?php

namespace App\Console\Commands;

use App\Models\Project;
use App\Models\QcFinalElectricalDailyCheck;
use App\Models\QcFinalElectricalNcr;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ImportQcFinalElectricalNcr extends Command
{
    protected $signature = 'qc:import-final-electrical-ncr
        {file : Path file XLSX}
        {--dry-run : Simulasi tanpa menyimpan database}';

    protected $description =
        'Import historical Detail NCR QC Final Elektrik dari workbook Excel';

    private array $projectCache = [];

    public function handle(): int
    {
        error_reporting(
            E_ALL
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

        $dryRun =
            (bool) $this->option(
                'dry-run'
            );

        $reader = new Xlsx();
        $reader->setReadDataOnly(true);

        $this->info(
            'Membaca workbook...'
        );

        $spreadsheet =
            $reader->load($path);

        $sheetName = collect(
            $spreadsheet->getSheetNames()
        )->first(
            fn (string $name) =>
                str_contains(
                    strtoupper($name),
                    'DETAIL NCR QC FINAL'
                )
        );

        if (! $sheetName) {
            $this->error(
                'Sheet Detail NCR tidak ditemukan.'
            );

            return self::FAILURE;
        }

        $sheet =
            $spreadsheet
                ->getSheetByName(
                    $sheetName
                );

        if (! $sheet) {
            return self::FAILURE;
        }

        $this->info(
            "Sheet: {$sheetName}"
        );

        $result = [
            'inserted' => 0,
            'updated' => 0,
            'skipped' => 0,
        ];

        $callback =
            function () use (
                $sheet,
                $sheetName,
                $dryRun,
                &$result
            ): void {
                $result =
                    $this->importRows(
                        $sheet,
                        $sheetName,
                        $dryRun
                    );
            };

        if ($dryRun) {
            $callback();
        } else {
            DB::transaction(
                $callback
            );
        }

        $this->newLine();

        $this->table(
            ['Hasil', 'Jumlah'],
            [
                [
                    'Inserted',
                    $result['inserted'],
                ],
                [
                    'Updated',
                    $result['updated'],
                ],
                [
                    'Skipped',
                    $result['skipped'],
                ],
            ]
        );

        if ($dryRun) {
            $this->warn(
                'Dry run selesai. Database tidak berubah.'
            );
        } else {
            $this->info(
                'Import Detail NCR selesai.'
            );
        }

        return self::SUCCESS;
    }

    private function importRows(
        Worksheet $sheet,
        string $sheetName,
        bool $dryRun
    ): array {
        /*
         * Berdasarkan workbook:
         *
         * Row 6 = header utama
         * Row 7 = header kategori defect
         * Row 8+ = data
         */
        $startRow = 8;

        $highestRow =
            $sheet->getHighestDataRow();

        $inserted = 0;
        $updated = 0;
        $skipped = 0;

        for (
            $row = $startRow;
            $row <= $highestRow;
            $row++
        ) {
            $ncrNumber =
                $this->nullableString(
                    $this->cellValue(
                        $sheet,
                        'B',
                        $row
                    )
                );

            if ($ncrNumber === null) {
                $skipped++;

                continue;
            }

            $issuedDate =
                $this->parseDate(
                    $this->cellValue(
                        $sheet,
                        'D',
                        $row
                    )
                );

            $productName =
                $this->nullableString(
                    $this->cellValue(
                        $sheet,
                        'E',
                        $row
                    )
                );

            if (
                $issuedDate === null
                || $productName === null
            ) {
                $skipped++;

                continue;
            }

            $projectName =
                $this->nullableString(
                    $this->cellValue(
                        $sheet,
                        'C',
                        $row
                    )
                )
                ?? '-';

            $legacyReference =
                hash(
                    'sha256',
                    implode(
                        '|',
                        [
                            'qc-final-electrical-ncr',
                            trim($sheetName),
                            $row,
                        ]
                    )
                );

            $projectId =
                $this->resolveProjectId(
                    $projectName
                );

            $dailyCheckId =
                $this->resolveDailyCheckId(
                    $ncrNumber
                );

            $payload = [
                'daily_check_id' =>
                    $dailyCheckId,

                'ncr_number' =>
                    $ncrNumber,

                'project_id' =>
                    $projectId,

                'project_name' =>
                    $projectName,

                'issued_date' =>
                    $issuedDate,

                'product_name' =>
                    $productName,

                'nonconformity_location' =>
                    $this->nullableString(
                        $this->cellValue(
                            $sheet,
                            'F',
                            $row
                        )
                    ),

                'target_unit' =>
                    $this->nullableString(
                        $this->cellValue(
                            $sheet,
                            'G',
                            $row
                        )
                    ),

                'nonconformity_description' =>
                    $this->nullableString(
                        $this->cellValue(
                            $sheet,
                            'H',
                            $row
                        )
                    ) ?? '-',

                'visual_qty' =>
                    $this->quantity(
                        $this->cellValue(
                            $sheet,
                            'I',
                            $row
                        )
                    ),

                'completeness_qty' =>
                    $this->quantity(
                        $this->cellValue(
                            $sheet,
                            'J',
                            $row
                        )
                    ),

                'specification_qty' =>
                    $this->quantity(
                        $this->cellValue(
                            $sheet,
                            'K',
                            $row
                        )
                    ),

                'dimension_qty' =>
                    $this->quantity(
                        $this->cellValue(
                            $sheet,
                            'L',
                            $row
                        )
                    ),

                'function_qty' =>
                    $this->quantity(
                        $this->cellValue(
                            $sheet,
                            'M',
                            $row
                        )
                    ),

                'component_status' =>
                    $this->normalizeStatus(
                        $this->cellValue(
                            $sheet,
                            'P',
                            $row
                        )
                    ),

                'inspector' =>
                    $this->nullableString(
                        $this->cellValue(
                            $sheet,
                            'Q',
                            $row
                        )
                    ),

                'cycle_time_minutes' =>
                    $this->nullableNumber(
                        $this->cellValue(
                            $sheet,
                            'S',
                            $row
                        )
                    ),

                'source' =>
                    'legacy_xlsx',

                'legacy_reference' =>
                    $legacyReference,

                'created_by' =>
                    null,

                'updated_by' =>
                    null,
            ];

            $existing =
                QcFinalElectricalNcr
                    ::withTrashed()
                    ->where(
                        'legacy_reference',
                        $legacyReference
                    )
                    ->first();

            if ($existing) {
                $updated++;

                if (! $dryRun) {
                    $existing->fill(
                        $payload
                    );

                    $existing->save();

                    if (
                        $existing->trashed()
                    ) {
                        $existing->restore();
                    }
                }

                continue;
            }

            $inserted++;

            if (! $dryRun) {
                QcFinalElectricalNcr
                    ::query()
                    ->create($payload);
            }
        }

        return compact(
            'inserted',
            'updated',
            'skipped'
        );
    }

    private function cellValue(
        Worksheet $sheet,
        string $column,
        int $row
    ): mixed {
        $cell =
            $sheet->getCell(
                "{$column}{$row}"
            );

        try {
            return $cell
                ->getCalculatedValue();
        } catch (\Throwable) {
            return $cell->getValue();
        }
    }

    private function resolveDailyCheckId(
        string $ncrNumber
    ): ?int {
        /*
         * Hubungkan otomatis hanya jika
         * tepat satu Daily Check mempunyai NCR yang sama.
         */
        $matches =
            QcFinalElectricalDailyCheck
                ::query()
                ->where(
                    'ncr_number',
                    $ncrNumber
                )
                ->limit(2)
                ->pluck('id');

        return $matches->count() === 1
            ? (int) $matches->first()
            : null;
    }

    private function resolveProjectId(
        string $projectName
    ): ?int {
        $key =
            $this->normalizeText(
                $projectName
            );

        if (
            array_key_exists(
                $key,
                $this->projectCache
            )
        ) {
            return $this->projectCache[$key];
        }

        $projects =
            Project::query()
                ->get([
                    'id',
                    'kode_proyek',
                    'nama_proyek',
                ]);

        foreach ($projects as $project) {
            if (
                $this->normalizeText(
                    $project->nama_proyek
                ) === $key
                || $this->normalizeText(
                    $project->kode_proyek
                ) === $key
            ) {
                return $this->projectCache[
                    $key
                ] = $project->id;
            }
        }

        return $this->projectCache[
            $key
        ] = null;
    }

    private function parseDate(
        mixed $value
    ): ?string {
        if (
            $value === null
            || $value === ''
            || $value === '-'
        ) {
            return null;
        }

        if (is_numeric($value)) {
            try {
                return Carbon::instance(
                    ExcelDate::excelToDateTimeObject(
                        (float) $value
                    )
                )->format('Y-m-d');
            } catch (\Throwable) {
                return null;
            }
        }

        try {
            return Carbon::parse(
                trim(
                    (string) $value
                )
            )->format('Y-m-d');
        } catch (\Throwable) {
            return null;
        }
    }

    private function normalizeStatus(
        mixed $value
    ): string {
        $value = strtolower(
            trim(
                (string) (
                    $value ?? ''
                )
            )
        );

        if ($value === 'ok') {
            return QcFinalElectricalNcr
                ::STATUS_OK;
        }

        if (
            $value === 'nok'
            || $value === 'not ok'
        ) {
            return QcFinalElectricalNcr
                ::STATUS_NOK;
        }

        return QcFinalElectricalNcr
            ::STATUS_PENDING;
    }

    private function quantity(
        mixed $value
    ): int {
        if (
            $value === null
            || $value === ''
            || $value === '-'
            || ! is_numeric($value)
        ) {
            return 0;
        }

        return max(
            0,
            (int) round(
                (float) $value
            )
        );
    }

    private function nullableNumber(
        mixed $value
    ): ?float {
        if (
            $value === null
            || $value === ''
            || $value === '-'
            || ! is_numeric($value)
        ) {
            return null;
        }

        return max(
            0,
            (float) $value
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

        $value = trim(
            (string) $value
        );

        return $value === ''
            || $value === '-'
                ? null
                : $value;
    }

    private function normalizeText(
        mixed $value
    ): string {
        return strtolower(
            preg_replace(
                '/\s+/',
                ' ',
                trim(
                    (string) (
                        $value ?? ''
                    )
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

        return base_path(
            $path
        );
    }
}
