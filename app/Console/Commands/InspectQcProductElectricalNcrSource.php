<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InspectQcProductElectricalNcrSource extends Command
{
    protected $signature = 'qc:inspect-product-electrical-ncr-source
        {file : Path file XLSX}
        {--sheet=* : Nama sheet tertentu}';

    protected $description =
        'Mencari sumber data NCR Visual, Dimensi, dan Fungsi pada workbook QC Product Elektrik';

    public function handle(): int
    {
        $path = $this->resolvePath(
            (string) $this->argument('file')
        );

        if (! is_file($path)) {
            $this->error(
                "File tidak ditemukan: {$path}"
            );

            return self::FAILURE;
        }

        $this->info('Membaca workbook...');
        $this->line($path);
        $this->newLine();

        $spreadsheet = IOFactory::load(
            $path
        );

        $requestedSheets = collect(
            $this->option('sheet')
        )
            ->filter()
            ->values();

        /*
         * Kalau --sheet tidak diberikan,
         * fokus pada dua sheet agregasi.
         */
        if ($requestedSheets->isEmpty()) {
            $requestedSheets = collect([
                'RESUME 2026',
                'DASHBOARD - IT',
            ]);
        }

        $foundAny = false;

        foreach ($requestedSheets as $sheetName) {
            $sheet = $spreadsheet
                ->getSheetByName(
                    $sheetName
                );

            if (! $sheet) {
                $this->warn(
                    "Sheet tidak ditemukan: {$sheetName}"
                );

                continue;
            }

            $this->info(
                "SCAN SHEET: {$sheetName}"
            );

            $found = $this->scanSheet(
                $sheet
            );

            if (! $found) {
                $this->line(
                    'Tidak ditemukan keyword NCR/Visual/Dimensi/Fungsi.'
                );
            } else {
                $foundAny = true;
            }

            $this->newLine(2);
        }

        if (! $foundAny) {
            $this->warn(
                'Keyword NCR belum ditemukan pada sheet target.'
            );
        }

        return self::SUCCESS;
    }

    private function scanSheet(
        Worksheet $sheet
    ): bool {
        $highestRow =
            $sheet->getHighestDataRow();

        $highestColumnIndex =
            Coordinate::columnIndexFromString(
                $sheet->getHighestDataColumn()
            );

        $keywords = [
            'ncr',
            'visual',
            'dimensi',
            'fungsi',
        ];

        $matches = [];

        for (
            $row = 1;
            $row <= $highestRow;
            $row++
        ) {
            for (
                $column = 1;
                $column <= $highestColumnIndex;
                $column++
            ) {
                $cell = $sheet->getCell([
                    $column,
                    $row,
                ]);

                $rawValue = $cell->getValue();

                if (
                    $rawValue === null
                    || $rawValue === ''
                ) {
                    continue;
                }

                $searchValue = strtolower(
                    trim(
                        (string) $rawValue
                    )
                );

                /*
                 * Formula juga bisa mempunyai
                 * hasil berupa keyword.
                 */
                try {
                    $calculatedValue =
                        $cell->getCalculatedValue();

                    $searchValue .= ' ' .
                        strtolower(
                            trim(
                                (string)
                                $calculatedValue
                            )
                        );
                } catch (\Throwable $exception) {
                    $calculatedValue = null;
                }

                foreach ($keywords as $keyword) {
                    if (
                        str_contains(
                            $searchValue,
                            $keyword
                        )
                    ) {
                        $matches[] = [
                            'row' => $row,
                            'column' => $column,
                            'coordinate' =>
                                $cell->getCoordinate(),
                            'raw' => $rawValue,
                            'calculated' =>
                                $calculatedValue,
                            'keyword' => $keyword,
                        ];

                        break;
                    }
                }
            }
        }

        if ($matches === []) {
            return false;
        }

        foreach ($matches as $match) {
            $this->newLine();

            $this->line(
                str_repeat('=', 80)
            );

            $this->info(
                sprintf(
                    'Match [%s] di %s!%s',
                    strtoupper(
                        $match['keyword']
                    ),
                    $sheet->getTitle(),
                    $match['coordinate']
                )
            );

            $this->line(
                'Raw: ' .
                $this->displayValue(
                    $match['raw']
                )
            );

            if (
                is_string($match['raw'])
                && str_starts_with(
                    $match['raw'],
                    '='
                )
            ) {
                $this->line(
                    'Formula: ' .
                    $match['raw']
                );

                $this->line(
                    'Calculated: ' .
                    $this->displayValue(
                        $match['calculated']
                    )
                );
            }

            $this->line(
                'Context:'
            );

            $this->showContext(
                $sheet,
                (int) $match['row'],
                (int) $match['column']
            );
        }

        return true;
    }

    private function showContext(
        Worksheet $sheet,
        int $centerRow,
        int $centerColumn
    ): void {
        $startRow = max(
            1,
            $centerRow - 2
        );

        $endRow = min(
            $sheet->getHighestDataRow(),
            $centerRow + 2
        );

        $startColumn = max(
            1,
            $centerColumn - 6
        );

        $highestColumnIndex =
            Coordinate::columnIndexFromString(
                $sheet->getHighestDataColumn()
            );

        $endColumn = min(
            $highestColumnIndex,
            $centerColumn + 6
        );

        for (
            $row = $startRow;
            $row <= $endRow;
            $row++
        ) {
            $parts = [];

            for (
                $column = $startColumn;
                $column <= $endColumn;
                $column++
            ) {
                $cell = $sheet->getCell([
                    $column,
                    $row,
                ]);

                $value = $cell->getValue();

                if (
                    $value === null
                    || $value === ''
                ) {
                    continue;
                }

                $coordinate =
                    $cell->getCoordinate();

                if (
                    $cell->getDataType()
                    === DataType::TYPE_FORMULA
                ) {
                    try {
                        $calculated =
                            $cell
                                ->getCalculatedValue();
                    } catch (\Throwable $exception) {
                        $calculated =
                            '[ERROR]';
                    }

                    $parts[] = sprintf(
                        '%s=%s => %s',
                        $coordinate,
                        $this->displayValue(
                            $value
                        ),
                        $this->displayValue(
                            $calculated
                        )
                    );
                } else {
                    $parts[] = sprintf(
                        '%s=%s',
                        $coordinate,
                        $this->displayValue(
                            $value
                        )
                    );
                }
            }

            if ($parts !== []) {
                $this->line(
                    'Row ' .
                    $row .
                    ': ' .
                    implode(
                        ' | ',
                        $parts
                    )
                );
            }
        }
    }

    private function displayValue(
        mixed $value
    ): string {
        if ($value === null) {
            return 'NULL';
        }

        if (is_bool($value)) {
            return $value
                ? 'TRUE'
                : 'FALSE';
        }

        $value = preg_replace(
            '/\s+/',
            ' ',
            trim(
                (string) $value
            )
        );

        if (
            mb_strlen($value) > 250
        ) {
            return mb_substr(
                $value,
                0,
                250
            ) . '...';
        }

        return $value;
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
}
