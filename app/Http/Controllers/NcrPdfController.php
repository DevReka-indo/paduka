<?php

namespace App\Http\Controllers;

use App\Models\Ncr;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class NcrPdfController extends Controller
{
    public function export(string $nomorNcr)
    {
        $ncr = Ncr::with(['user', 'penanggungJawab', 'project', 'unitKerja'])
            ->where('nomor_ncr', $nomorNcr)
            ->firstOrFail();

        $status = strtolower($ncr->keterangan ?? '');

        $qrOpen = $this->buildQrPng(
            nomorNcr: $ncr->nomor_ncr,
            type: 'open',
        );

        $qrProcess = null;
        if (in_array($status, ['process', 'close', 'closed']) && !empty($ncr->signed_at_process)) {
            $qrProcess = $this->buildQrPng(
                nomorNcr: $ncr->nomor_ncr,
                type: 'process',
            );
        }

        $qrClose = null;
        if (in_array($status, ['close', 'closed']) && !empty($ncr->signed_at_close)) {
            $qrClose = $this->buildQrPng(
                nomorNcr: $ncr->nomor_ncr,
                type: 'close',
            );
        }

        $pdf = Pdf::loadView('ncr.pdf', compact(
            'ncr',
            'status',
            'qrOpen',
            'qrProcess',
            'qrClose'
        ))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => false,
                'defaultFont' => 'DejaVu Sans',
                'dpi' => 120,
                'isPhpEnabled' => true,
            ]);

        $filename = 'NCR-' . str_replace('/', '-', $ncr->nomor_ncr) . '.pdf';

        return $pdf->stream($filename);
    }

    private function buildQrPng(string $nomorNcr, string $type): string
    {
        $token = Crypt::encryptString(json_encode([
            'nomor_ncr' => $nomorNcr,
            'type' => $type,
        ]));

        $url = route('signature.show', ['token' => $token]);

        $png = QrCode::format('png')
            ->size(300)
            ->margin(3)
            ->errorCorrection('M')
            ->generate($url);

        return 'data:image/png;base64,' . base64_encode($png);
    }
}
