<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class DurabilityImport implements ToCollection, WithHeadingRow
{
    private array $rows = [];

    public function collection(Collection $rows): void
    {
        $parsedRows = [];

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2;

            if ($row->filter(fn ($value) => $value !== null && $value !== '')->isEmpty()) {
                continue;
            }

            $data = [
                'row_number' => $rowNumber,

                'tahun' => $this->toInt($this->getValue($row, ['tahun'])),
                'nomor_po' => $this->toNullableString($this->getValue($row, ['nomor_po', 'nomor po'])),
                'nama_produk' => $this->toNullableString($this->getValue($row, ['nama_produk', 'nama produk'])),
                'detail_komponen' => $this->toNullableString($this->getValue($row, ['detail_komponen', 'detail komponen'])),
                'lokasi' => $this->toNullableString($this->getValue($row, ['lokasi'])),
                'car' => $this->toNullableString($this->getValue($row, ['car'])),
                'trainset' => $this->toInt($this->getValue($row, ['trainset'])),
                'tgl_kerusakan' => $this->toDate($this->getValue($row, ['tgl_kerusakan', 'tgl kerusakan'])),
                'tgl_terbit_lppb' => $this->toDate($this->getValue($row, ['tgl_terbit_lppb', 'tgl terbit lppb'])),
                'customer' => $this->toNullableString($this->getValue($row, ['customer'])),
                'proyek' => $this->toNullableString($this->getValue($row, ['proyek'])),
                'case_keterangan' => $this->toNullableString($this->getValue($row, ['case_keterangan', 'case keterangan'])),
                'rentang_penggantian' => $this->toIntNullable($this->getValue($row, ['rentang_penggantian', 'rentang penggantian'])),
                'jumlah_penggantian' => $this->toIntNullable($this->getValue($row, ['jumlah_penggantian', 'jumlah penggantian'])),
            ];

            Validator::make($data, [
                'tahun' => ['required', 'integer'],
                'nama_produk' => ['required', 'string'],
                'detail_komponen' => ['required', 'string'],
                'lokasi' => ['required', 'string'],
                'car' => ['nullable', 'string'],
                'trainset' => ['required', 'integer'],
                'tgl_kerusakan' => ['nullable', 'date'],
                'tgl_terbit_lppb' => ['nullable', 'date'],
                'customer' => ['nullable', 'string'],
                'proyek' => ['required', 'string'],
                'nomor_po' => ['nullable', 'string'],
                'case_keterangan' => ['nullable', 'string'],
                'rentang_penggantian' => ['nullable', 'integer'],
                'jumlah_penggantian' => ['nullable', 'integer'],
            ], [
                'required' => ':attribute wajib diisi.',
                'integer' => ':attribute harus berupa angka bulat.',
                'date' => ':attribute harus berupa tanggal valid.',
            ], [
                'tahun' => "TAHUN baris {$rowNumber}",
                'nama_produk' => "NAMA PRODUK baris {$rowNumber}",
                'detail_komponen' => "DETAIL KOMPONEN baris {$rowNumber}",
                'lokasi' => "LOKASI baris {$rowNumber}",
                'car' => "CAR baris {$rowNumber}",
                'trainset' => "TRAINSET baris {$rowNumber}",
                'tgl_kerusakan' => "TGL KERUSAKAN baris {$rowNumber}",
                'tgl_terbit_lppb' => "TGL TERBIT LPPB baris {$rowNumber}",
                'customer' => "CUSTOMER baris {$rowNumber}",
                'proyek' => "PROYEK baris {$rowNumber}",
                'nomor_po' => "NOMOR PO baris {$rowNumber}",
                'case_keterangan' => "CASE KETERANGAN baris {$rowNumber}",
                'rentang_penggantian' => "RENTANG PENGGANTIAN baris {$rowNumber}",
                'jumlah_penggantian' => "JUMLAH PENGGANTIAN baris {$rowNumber}",
            ])->validate();

            $parsedRows[] = $data;
        }

        if (empty($parsedRows)) {
            throw ValidationException::withMessages([
                'file' => 'File Excel tidak memiliki data yang bisa di-import.',
            ]);
        }

        $this->rows = $parsedRows;
    }

    public function rows(): array
    {
        return $this->rows;
    }

    private function getValue(Collection $row, array $possibleKeys)
    {
        foreach ($possibleKeys as $key) {
            $normalizedKey = str($key)
                ->lower()
                ->replace(' ', '_')
                ->toString();

            if ($row->has($normalizedKey)) {
                return $row[$normalizedKey];
            }
        }

        return null;
    }

    private function toNullableString($value): ?string
    {
        if ($value === null) {
            return null;
        }

        if (is_numeric($value)) {
            $value = number_format((float) $value, 0, '', '');
        }

        $value = trim((string) $value);

        return $value === '' || strtoupper($value) === 'NULL' ? null : $value;
    }

    private function toInt($value): ?int
    {
        if ($value === null || $value === '' || strtoupper((string) $value) === 'NULL') {
            return null;
        }

        return (int) $value;
    }

    private function toIntNullable($value): ?int
    {
        if ($value === null || $value === '' || strtoupper((string) $value) === 'NULL') {
            return null;
        }

        $value = trim((string) $value);
        $value = preg_replace('/[^0-9\-]/', '', $value);

        return $value === '' ? null : (int) $value;
    }

    private function toDate($value): ?string
    {
        if ($value === null || $value === '' || strtoupper((string) $value) === 'NULL') {
            return null;
        }

        if (is_numeric($value)) {
            return ExcelDate::excelToDateTimeObject($value)->format('Y-m-d');
        }

        $timestamp = strtotime((string) $value);

        return $timestamp === false ? null : date('Y-m-d', $timestamp);
    }
}
