<?php

namespace App\Console\Commands;

use App\Models\Ncr;
use App\Models\User;
use App\Notifications\NcrTerlambatReminderNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class KirimReminderNcrTerlambat extends Command
{
    protected $signature = 'ncr:reminder-terlambat';
    protected $description = 'Kirim reminder NCR yang terlambat setiap kelipatan 7 hari selama masih open';

    public function handle(): int
    {
        $today = Carbon::today();

        $ncrList = Ncr::with('unitKerja')
            ->where('keterangan', 'open')
            ->whereNotNull('tgl_target')
            ->get();

        $totalNotif = 0;

        foreach ($ncrList as $ncr) {
            $targetDate = Carbon::parse($ncr->tgl_target)->startOfDay();
            $hariTerlambat = $targetDate->diffInDays($today, false);

            // hanya kirim kalau sudah lewat minimal 7 hari dan kelipatan 7
            if ($hariTerlambat < 7 || $hariTerlambat % 7 !== 0) {
                continue;
            }

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

            foreach ($recipients as $user) {
                $user->notify(new NcrTerlambatReminderNotification($ncr, $hariTerlambat));
                $totalNotif++;
            }

            $this->info("Reminder NCR {$ncr->nomor_ncr} dikirim untuk keterlambatan {$hariTerlambat} hari.");
        }

        $this->info("Selesai. Total notifikasi terkirim: {$totalNotif}");

        return self::SUCCESS;
    }
}
