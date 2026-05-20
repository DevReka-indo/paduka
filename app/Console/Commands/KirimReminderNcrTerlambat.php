<?php

namespace App\Console\Commands;

use App\Models\Ncr;
use App\Models\User;
use App\Notifications\NcrTerlambatReminderNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Throwable;

class KirimReminderNcrTerlambat extends Command
{
    protected $signature = 'ncr:reminder-terlambat';
    protected $description = 'Kirim reminder NCR yang terlambat setiap kelipatan 7 hari selama masih open';

    public function handle(): int
    {
        $today = Carbon::today();

        $logger = Log::channel('ncr_reminder');

        $logger->info('Mulai proses reminder NCR terlambat', [
            'tanggal_proses' => $today->format('Y-m-d'),
        ]);

        $ncrList = Ncr::with('unitKerja')
            ->where('keterangan', 'open')
            ->whereNotNull('tgl_target')
            ->get();

        $totalNcrDicek = $ncrList->count();
        $totalNcrTerlambat = 0;
        $totalNotifBerhasil = 0;
        $totalNotifGagal = 0;
        $totalTanpaPenerima = 0;

        foreach ($ncrList as $ncr) {
            $targetDate = Carbon::parse($ncr->tgl_target)->startOfDay();
            $hariTerlambat = $targetDate->diffInDays($today, false);

            if ($hariTerlambat < 7 || $hariTerlambat % 7 !== 0) {
                $logger->info('NCR dilewati karena belum masuk jadwal reminder', [
                    'nomor_ncr' => $ncr->nomor_ncr,
                    'tgl_target' => $targetDate->format('Y-m-d'),
                    'hari_terlambat' => $hariTerlambat,
                ]);

                continue;
            }

            $totalNcrTerlambat++;

            $recipients = User::query()
                ->whereIn('level', ['user', 'manager'])
                ->whereHas('unitKerja', function ($q) use ($ncr) {
                    if (!is_null($ncr->unit_kerja_id)) {
                        $q->where('unit_kerja.id', $ncr->unit_kerja_id);
                    } elseif (!empty($ncr->unit_tujuan)) {
                        $q->where('nama_unit', $ncr->unit_tujuan);
                    }
                })
                ->whereNotNull('email')
                ->get()
                ->unique('id');

            if ($recipients->isEmpty()) {
                $totalTanpaPenerima++;

                $logger->warning('NCR terlambat tetapi tidak memiliki penerima email', [
                    'nomor_ncr' => $ncr->nomor_ncr,
                    'unit_kerja_id' => $ncr->unit_kerja_id,
                    'unit_tujuan' => $ncr->unit_tujuan,
                    'tgl_target' => $targetDate->format('Y-m-d'),
                    'hari_terlambat' => $hariTerlambat,
                ]);

                continue;
            }

            foreach ($recipients as $user) {
                try {
                    $user->notify(new NcrTerlambatReminderNotification($ncr, $hariTerlambat));

                    $totalNotifBerhasil++;

                    $logger->info('Reminder NCR berhasil dikirim', [
                        'nomor_ncr' => $ncr->nomor_ncr,
                        'user_id' => $user->id,
                        'user_name' => $user->name ?? null,
                        'email' => $user->email,
                        'level' => $user->level,
                        'tgl_target' => $targetDate->format('Y-m-d'),
                        'hari_terlambat' => $hariTerlambat,
                    ]);
                } catch (Throwable $e) {
                    $totalNotifGagal++;

                    $logger->error('Reminder NCR gagal dikirim', [
                        'nomor_ncr' => $ncr->nomor_ncr,
                        'user_id' => $user->id,
                        'user_name' => $user->name ?? null,
                        'email' => $user->email,
                        'level' => $user->level,
                        'tgl_target' => $targetDate->format('Y-m-d'),
                        'hari_terlambat' => $hariTerlambat,
                        'error_message' => $e->getMessage(),
                        'error_file' => $e->getFile(),
                        'error_line' => $e->getLine(),
                    ]);
                }
            }

            $this->info("Reminder NCR {$ncr->nomor_ncr} diproses untuk keterlambatan {$hariTerlambat} hari.");
        }

        $logger->info('Selesai proses reminder NCR terlambat', [
            'tanggal_proses' => $today->format('Y-m-d'),
            'total_ncr_dicek' => $totalNcrDicek,
            'total_ncr_masuk_jadwal_reminder' => $totalNcrTerlambat,
            'total_notifikasi_berhasil' => $totalNotifBerhasil,
            'total_notifikasi_gagal' => $totalNotifGagal,
            'total_ncr_tanpa_penerima' => $totalTanpaPenerima,
        ]);

        $this->info("Selesai. Total notifikasi berhasil: {$totalNotifBerhasil}. Gagal: {$totalNotifGagal}.");

        return self::SUCCESS;
    }
}
