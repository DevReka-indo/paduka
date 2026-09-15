<?php

namespace App\Console\Commands;

use App\Models\Project;
use App\Models\QcFinalElectricalDailyCheck;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ImportQcFinalElectricalDailyCheck extends Command
{
    protected $signature = 'qc:import-final-electrical-daily
        {file : Path file XLSX}
        {--sheet=* : Import sheet tertentu}
        {--dry-run : Simulasi tanpa menyimpan database}';

    protected $description =
        'Import historical Daily Check QC Final Elektrik dari workbook Excel';

    private array $projectCache = [];

    public function handle(): int
    {
        /*
         * PhpSpreadsheet versi project saat ini menghasilkan
         * deprecation warning pada PHP 8.5.
         */
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

        $dryRun = (bool) $this->option(
            'dry-run'
        );

        $reader = new Xlsx();
        $reader->setReadDataOnly(true);

        $this->info('Membaca workbook...');

        $spreadsheet = $reader->load(
            $path
        );

        $sheetNames = collect(
            $spreadsheet->getSheetNames()
        )
            ->filter(
                fn (string $name) =>
                    preg_match(
                        '/^Daily Check\s*-\s*.+\s+2026\s*$/i',
                        trim($name)
                    ) === 1
            );

        $requestedSheets = collect(
            $this->option('sheet')
        )
            ->filter()
            ->values();

        if ($requestedSheets->isNotEmpty()) {
            $sheetNames = $sheetNames
                ->filter(
                    fn (string $name) =>
                        $requestedSheets
                            ->contains($name)
                );
        }

        if ($sheetNames->isEmpty()) {
            $this->error(
                'Tidak ditemukan sheet Daily Check tahun 2026.'
            );

            return self::FAILURE;
        }

        $this->newLine();

        $this->info(
            $dryRun
                ? 'MODE DRY RUN'
                : 'MODE IMPORT DATABASE'
        );

        $this->newLine();

        $totalInserted = 0;
        $totalUpdated = 0;
        $totalSkipped = 0;

        $importCallback = function () use (
            $spreadsheet,
            $sheetNames,
            $dryRun,
            &$totalInserted,
            &$totalUpdated,
            &$totalSkipped
        ): void {
            foreach ($sheetNames as $sheetName) {
                $sheet = $spreadsheet
                    ->getSheetByName(
                        $sheetName
                    );

                if (! $sheet) {
                    continue;
                }

                $result = $this->importSheet(
                    $sheet,
                    $sheetName,
                    $dryRun
                );

                $totalInserted +=
                    $result['inserted'];

                $totalUpdated +=
                    $result['updated'];

                $totalSkipped +=
                    $result['skipped'];
            }
        };

        if ($dryRun) {
            $importCallback();
        } else {
            DB::transaction(
                $importCallback
            );
        }

        $this->newLine();

        $this->table(
            ['Hasil', 'Jumlah'],
            [
                [
                    'Inserted',
                    $totalInserted,
                ],
                [
                    'Updated',
                    $totalUpdated,
                ],
                [
                    'Skipped',
                    $totalSkipped,
                ],
            ]
        );

        if ($dryRun) {
            $this->warn(
                'Dry run selesai. Database tidak berubah.'
            );
        } else {
            $this->info(
                'Import Daily Check selesai.'
            );
        }

        return self::SUCCESS;
    }

    private function importSheet(
        Worksheet $sheet,
        string $sheetName,
        bool $dryRun
    ): array {
        $headerRow =
            $this->findHeaderRow(
                $sheet
            );

        if ($headerRow === null) {
            $this->warn(
                "{$sheetName}: header tidak ditemukan."
            );

            return [
                'inserted' => 0,
                'updated' => 0,
                'skipped' => 0,
            ];
        }

        $columns =
            $this->mapColumns(
                $sheet,
                $headerRow
            );

        if (
            ! isset(
                $columns['check_date']
            )
            || ! isset(
                $columns['product_name']
            )
        ) {
            $this->warn(
                "{$sheetName}: kolom wajib tidak ditemukan."
            );

            return [
                'inserted' => 0,
                'updated' => 0,
                'skipped' => 0,
            ];
        }

        $inserted = 0;
        $updated = 0;
        $skipped = 0;

        $highestRow =
            $sheet->getHighestDataRow();

        for (
            $row = $headerRow + 1;
            $row <= $highestRow;
            $row++
        ) {
            $checkDate =
                $this->parseDate(
                    $this->value(
                        $sheet,
                        $columns,
                        'check_date',
                        $row
                    )
                );

            $productName =
                $this->nullableString(
                    $this->value(
                        $sheet,
                        $columns,
                        'product_name',
                        $row
                    )
                );

            /*
             * Banyak workbook mempunyai formatting
             * sampai ribuan baris kosong.
             */
            if (
                $checkDate === null
                || $productName === null
            ) {
                $skipped++;

                continue;
            }

            $projectName =
                $this->nullableString(
                    $this->value(
                        $sheet,
                        $columns,
                        'project_name',
                        $row
                    )
                )
                ?? '-';

            $projectId =
                $this->resolveProjectId(
                    $projectName
                );

            $legacyReference =
                hash(
                    'sha256',
                    implode(
                        '|',
                        [
                            'qc-final-electrical-daily',
                            trim($sheetName),
                            $row,
                        ]
                    )
                );

            $payload = [
                'check_date' =>
                    $checkDate,

                'project_id' =>
                    $projectId,

                'project_name' =>
                    $projectName,

                'document_check' =>
                    $this->nullableString(
                        $this->value(
                            $sheet,
                            $columns,
                            'document_check',
                            $row
                        )
                    ) ?? '-',

                'inspection_gate' =>
                    $this->nullableString(
                        $this->value(
                            $sheet,
                            $columns,
                            'inspection_gate',
                            $row
                        )
                    ) ?? '-',

                'product_name' =>
                    $productName,

                'check_category' =>
                    $this->nullableString(
                        $this->value(
                            $sheet,
                            $columns,
                            'check_category',
                            $row
                        )
                    ) ?? 'Final Test',

                'oil_description' =>
                    $this->nullableString(
                        $this->value(
                            $sheet,
                            $columns,
                            'oil_description',
                            $row
                        )
                    ),

                'serial_number' =>
                    $this->nullableString(
                        $this->value(
                            $sheet,
                            $columns,
                            'serial_number',
                            $row
                        )
                    ),

                'car_reference' =>
                    $this->nullableString(
                        $this->value(
                            $sheet,
                            $columns,
                            'car_reference',
                            $row
                        )
                    ),

                'batch_reference' =>
                    $this->nullableString(
                        $this->value(
                            $sheet,
                            $columns,
                            'batch_reference',
                            $row
                        )
                    ),

                'visual_qty' =>
                    $this->quantity(
                        $this->value(
                            $sheet,
                            $columns,
                            'visual_qty',
                            $row
                        )
                    ),

                'completeness_qty' =>
                    $this->quantity(
                        $this->value(
                            $sheet,
                            $columns,
                            'completeness_qty',
                            $row
                        )
                    ),

                'belltest_qty' =>
                    $this->quantity(
                        $this->value(
                            $sheet,
                            $columns,
                            'belltest_qty',
                            $row
                        )
                    ),

                'function_qty' =>
                    $this->quantity(
                        $this->value(
                            $sheet,
                            $columns,
                            'function_qty',
                            $row
                        )
                    ),

                'torque_qty' =>
                    $this->quantity(
                        $this->value(
                            $sheet,
                            $columns,
                            'torque_qty',
                            $row
                        )
                    ),

                'closing_oil_date' =>
                    $this->parseDate(
                        $this->value(
                            $sheet,
                            $columns,
                            'closing_oil_date',
                            $row
                        )
                    ),

                'oil_count' =>
                    $this->quantity(
                        $this->value(
                            $sheet,
                            $columns,
                            'oil_count',
                            $row
                        )
                    ),

                'ncr_number' =>
                    $this->nullableString(
                        $this->value(
                            $sheet,
                            $columns,
                            'ncr_number',
                            $row
                        )
                    ),

                'result' =>
                    $this->normalizeResult(
                        $this->value(
                            $sheet,
                            $columns,
                            'result',
                            $row
                        )
                    ),

                'inspector' =>
                    $this->nullableString(
                        $this->value(
                            $sheet,
                            $columns,
                            'inspector',
                            $row
                        )
                    ) ?? '-',

                'status_is' =>
                    $this->nullableString(
                        $this->value(
                            $sheet,
                            $columns,
                            'status_is',
                            $row
                        )
                    ),

                'oil_link' =>
                    $this->nullableString(
                        $this->value(
                            $sheet,
                            $columns,
                            'oil_link',
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
                QcFinalElectricalDailyCheck
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
                QcFinalElectricalDailyCheck
                    ::query()
                    ->create($payload);
            }
        }

        $this->line(
            sprintf(
                '%-35s Inserted: %4d | Updated: %4d | Skipped: %4d',
                $sheetName,
                $inserted,
                $updated,
                $skipped
            )
        );

        return compact(
            'inserted',
            'updated',
            'skipped'
        );
    }

    private function findHeaderRow(
        Worksheet $sheet
    ): ?int {
        $maxRow = min(
            25,
            $sheet->getHighestDataRow()
        );

        $maxColumn =
            \PhpOffice\PhpSpreadsheet\Cell\Coordinate
                ::columnIndexFromString(
                    $sheet->getHighestDataColumn()
                );

        for (
            $row = 1;
            $row <= $maxRow;
            $row++
        ) {
            $values = [];

            for (
                $column = 1;
                $column <= $maxColumn;
                $column++
            ) {
                $value =
                    $sheet->getCell([
                        $column,
                        $row,
                    ])->getValue();

                if (
                    $value === null
                    || $value === ''
                ) {
                    continue;
                }

                $values[] =
                    $this->normalizeHeader(
                        $value
                    );
            }

            if (
                in_array(
                    'check date',
                    $values,
                    true
                )
                && in_array(
                    'project',
                    $values,
                    true
                )
            ) {
                return $row;
            }
        }

        return null;
    }

    private function mapColumns(
        Worksheet $sheet,
        int $headerRow
    ): array {
        $aliases = [
            'check_date' => [
                'check date',
            ],

            'project_name' => [
                'project',
            ],

            'document_check' => [
                'doc check',
                'document check',
            ],

            'inspection_gate' => [
                'inspection gate',
            ],

            'product_name' => [
                'product name',
                'nama produk',
            ],

            'check_category' => [
                'check category',
            ],

            'oil_description' => [
                'oil',
            ],

            'serial_number' => [
                'sn',
                's n',
                'serial number',
            ],

            'car_reference' => [
                'car',
            ],

            'batch_reference' => [
                'ts batch',
                'ts / batch',
                'ts',
                'batch',
            ],

            'visual_qty' => [
                'visual',
            ],

            'completeness_qty' => [
                'kelengkapan',
            ],

            'belltest_qty' => [
                'beltes',
                'belltest',
                'bell test',
            ],

            'function_qty' => [
                'fungsi',
                'function',
            ],

            'torque_qty' => [
                'torsi',
                'torque',
            ],

            'closing_oil_date' => [
                'closing oil date',
                'closing oil / date',
                'closing oil',
            ],

            'oil_count' => [
                'jumlah oil',
            ],

            'ncr_number' => [
                'no ncr',
                'nomor ncr',
            ],

            'result' => [
                'result',
            ],

            'inspector' => [
                'inspector',
            ],

            'status_is' => [
                'status is',
            ],

            'oil_link' => [
                'link oil',
            ],
        ];

        $result = [];

        $highestColumn =
            \PhpOffice\PhpSpreadsheet\Cell\Coordinate
                ::columnIndexFromString(
                    $sheet->getHighestDataColumn()
                );

        for (
            $column = 1;
            $column <= $highestColumn;
            $column++
        ) {
            $header =
                $this->normalizeHeader(
                    $sheet->getCell([
                        $column,
                        $headerRow,
                    ])->getValue()
                );

            foreach (
                $aliases as $field => $names
            ) {
                if (
                    in_array(
                        $header,
                        $names,
                        true
                    )
                    && ! isset(
                        $result[$field]
                    )
                ) {
                    $result[$field] =
                        $column;

                    break;
                }
            }
        }

        return $result;
    }

    private function value(
        Worksheet $sheet,
        array $columns,
        string $field,
        int $row
    ): mixed {
        if (
            ! isset(
                $columns[$field]
            )
        ) {
            return null;
        }

        $cell = $sheet->getCell([
            $columns[$field],
            $row,
        ]);

        try {
            return $cell
                ->getCalculatedValue();
        } catch (\Throwable) {
            return $cell->getValue();
        }
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

        if (
            is_numeric($value)
        ) {
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

        $value = trim(
            (string) $value
        );

        foreach (
            [
                'Y-m-d',
                'd/m/Y',
                'd-m-Y',
                'd M Y',
                'd F Y',
            ] as $format
        ) {
            try {
                return Carbon::createFromFormat(
                    $format,
                    $value
                )->format('Y-m-d');
            } catch (\Throwable) {
                // Try next format.
            }
        }

        try {
            return Carbon::parse(
                $value
            )->format('Y-m-d');
        } catch (\Throwable) {
            return null;
        }
    }

    private function quantity(
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

    private function normalizeResult(
        mixed $value
    ): string {
        $value = strtolower(
            trim(
                (string) (
                    $value ?? ''
                )
            )
        );

        if (
            $value === 'nok'
            || str_contains(
                $value,
                'not ok'
            )
        ) {
            return QcFinalElectricalDailyCheck
                ::RESULT_NOK;
        }

        if ($value === 'ok') {
            return QcFinalElectricalDailyCheck
                ::RESULT_OK;
        }

        return QcFinalElectricalDailyCheck
            ::RESULT_PENDING;
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

        $projects = Project::query()
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

    private function normalizeHeader(
        mixed $value
    ): string {
        $value = strtolower(
            trim(
                (string) (
                    $value ?? ''
                )
            )
        );

        $value = str_replace(
            [
                '.',
                '_',
                '-',
            ],
            ' ',
            $value
        );

        $value = preg_replace(
            '/\s*\/\s*/',
            ' / ',
            $value
        );

        return preg_replace(
            '/\s+/',
            ' ',
            trim($value)
        );
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
