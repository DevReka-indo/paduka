<?php

namespace App\Http\Controllers;

use App\Models\Ncr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class SignatureController extends Controller
{
    public function show(string $token)
    {
        try {
            $payload = json_decode(Crypt::decryptString($token), true);

            if (!$payload || !isset($payload['nomor_ncr'], $payload['type'])) {
                abort(404, 'Data signature tidak valid.');
            }

            $ncr = Ncr::with(['user', 'penanggungJawab', 'project', 'unitKerja'])
                ->where('nomor_ncr', $payload['nomor_ncr'])
                ->firstOrFail();

            $type = $payload['type'];

            $signature = match ($type) {
                'open' => [
                    'label' => 'Pembuat NCR',
                    'name' => $ncr->user->name ?? 'N/A',
                    'jabatan' => $ncr->user->jabatan ?? '-',
                    'timestamp' => $ncr->signed_at_open ?? $ncr->tgl_masuk,
                ],
                'process' => [
                    'label' => 'Unit Yang Dituju',
                    'name' => $ncr->managerTgp->name ?? 'N/A',
                    'jabatan' => $ncr->managerTgp->jabatan ?? '-',
                    'timestamp' => $ncr->signed_at_process,
                ],
                'close' => [
                    'label' => 'Verifikasi Penutup',
                    'name' => $ncr->user->name ?? 'N/A',
                    'jabatan' => $ncr->user->jabatan ?? '-',
                    'timestamp' => $ncr->signed_at_close,
                ],
                default => null,
            };

            if (!$signature) {
                abort(404, 'Tipe signature tidak ditemukan.');
            }

            $signature['timestamp_formatted'] = !empty($signature['timestamp'])
                ? Carbon::parse($signature['timestamp'])
                    ->setTimezone('Asia/Jakarta')
                    ->format('d/m/Y H:i:s') . ' WIB'
                : '-';

            return view('signature.show', [
                'ncr' => $ncr,
                'type' => $type,
                'signature' => $signature,
            ]);
        } catch (DecryptException $e) {
            abort(404, 'Token signature tidak valid atau rusak.');
        }
    }
}
