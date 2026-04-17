<?php

namespace App\Exports;

use App\Models\Ncr;
use Maatwebsite\Excel\Concerns\{
    FromCollection,
    WithHeadings,
    WithMapping,
    ShouldAutoSize,
    WithStyles,
    WithEvents,
    WithColumnFormatting,
    WithCustomValueBinder
};
use Maatwebsite\Excel\Events\AfterSheet;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\{
    Border,
    Alignment,
    Fill,
    NumberFormat
};
use PhpOffice\PhpSpreadsheet\Cell\{
    Cell,
    DataType,
    StringValueBinder
};

class NcrReportExport extends StringValueBinder implements
    FromCollection,
    WithHeadings,
    WithMapping,
    ShouldAutoSize,
    WithStyles,
    WithEvents,
    WithColumnFormatting,
    WithCustomValueBinder
{
    protected $tglAwal;
    protected $tglAkhir;
    protected $ketNCR;
    protected $no = 1;

    public function __construct($tglAwal = null, $tglAkhir = null, $ketNCR = null)
    {
        $this->tglAwal = $tglAwal;
        $this->tglAkhir = $tglAkhir;
        $this->ketNCR = $ketNCR;
    }

    /**
     * Paksa kolom B jadi string TANPA pakai '
     */
    public function bindValue(Cell $cell, $value)
    {
        if ($cell->getColumn() === 'B') {
            $cell->setValueExplicit((string) $value, DataType::TYPE_STRING);
            return true;
        }

        return parent::bindValue($cell, $value);
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function collection()
    {
        return Ncr::with(['user', 'project'])
            ->when($this->tglAwal, fn($q) => $q->whereDate('tgl_masuk', '>=', $this->tglAwal))
            ->when($this->tglAkhir, fn($q) => $q->whereDate('tgl_masuk', '<=', $this->tglAkhir))
            ->when($this->ketNCR && $this->ketNCR !== 'all', fn($q) => $q->where('keterangan', $this->ketNCR))
            ->orderBy('tgl_masuk')
            ->orderBy('nomor_ncr')
            ->get();
    }

    public function headings(): array
    {
        return [
            ['Laporan Data NCR'],
            [],
            [
                '#',
                'Nomor',
                'Nama / Kode Proyek',
                'Tanggal Terbit',
                'Nama Produk/Proses',
                'Nama Inspektor',
                'Lokasi Ketidaksesuaian',
                'Unit Yang Dituju',
                'Uraian Ketidaksesuaian',
                'Status',
            ],
        ];
    }

    public function map($ncr): array
    {
        return [
            $this->no++,
            $ncr->nomor_ncr,

            ($ncr->project->nama_proyek ?? '-') . ' / ' . ($ncr->kode_proyek ?? '-'),

            $ncr->tgl_masuk?->format('d-m-Y') ?? '-',

            $ncr->nama_proses ?? '-',
            $ncr->user->name ?? '-',

            // sesuai request kamu
            $ncr->status_temuan ?? '-',
            $ncr->unit_tujuan ?? '-',

            $ncr->uraian ?? '-',
            strtoupper($ncr->keterangan ?? '-'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 14],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
            3 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F46E5'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function ($event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();

                // Title
                $sheet->mergeCells('A1:J1');
                $sheet->getRowDimension(1)->setRowHeight(24);

                // Header height
                $sheet->getRowDimension(3)->setRowHeight(22);

                // Border
                $sheet->getStyle("A3:J{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'D1D5DB'],
                        ],
                    ],
                ]);

                // Wrap text
                $sheet->getStyle("A3:J{$lastRow}")
                    ->getAlignment()
                    ->setWrapText(true);

                // Alignment
                $sheet->getStyle("A4:B{$lastRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->getStyle("D4:D{$lastRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->getStyle("J4:J{$lastRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Freeze + filter
                $sheet->freezePane('A4');
                $sheet->setAutoFilter("A3:J3");

                // Column width
                foreach ([
                    'A' => 6, 'B' => 20, 'C' => 32, 'D' => 16,
                    'E' => 28, 'F' => 24, 'G' => 30, 'H' => 24,
                    'I' => 45, 'J' => 14
                ] as $col => $width) {
                    $sheet->getColumnDimension($col)->setWidth($width);
                }
            },
        ];
    }
}
