<?php

namespace App\Notifications;

use App\Models\Ncr;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NcrTerlambatReminderNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Ncr $ncr,
        public int $hariTerlambat
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    // public function toMail(object $notifiable): MailMessage
    // {
    //     return (new MailMessage)
    //         ->subject('Peringatan NCR Terlambat Ditindaklanjuti')
    //         ->greeting('Halo ' . ($notifiable->name ?? 'User') . ',')
    //         ->line('Terdapat NCR yang belum ditindaklanjuti dan telah melewati target penyelesaian.')
    //         ->line('Nomor NCR: ' . $this->ncr->nomor_ncr)
    //         ->line('Tanggal target: ' . optional($this->ncr->tgl_target)->format('Y-m-d'))
    //         ->line('Keterlambatan: ' . $this->hariTerlambat . ' hari')
    //         ->action('Lihat Detail NCR', route('ncr.show', $this->ncr->nomor_ncr))
    //         ->line('Mohon segera dilakukan tindak lanjut.');
    // }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Peringatan NCR Terlambat Ditindaklanjuti')
            ->view('emails.terlambat', [
                'notifiable' => $notifiable,
                'ncr' => $this->ncr,
                'url' => route('ncr.show', $this->ncr->nomor_ncr),
                'tanggal_target' => optional($this->ncr->tgl_target)->format('Y-m-d') ?? '-',
                'hari_terlambat' => $this->hariTerlambat,
            ]);
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Peringatan NCR terlambat',
            'message' => 'NCR ' . $this->ncr->nomor_ncr . ' telah terlambat ' . $this->hariTerlambat . ' hari.',
            'nomor_ncr' => $this->ncr->nomor_ncr,
            'hari_terlambat' => $this->hariTerlambat,
            'url' => route('ncr.show', $this->ncr->nomor_ncr),
            'type' => 'ncr_terlambat',
        ];
    }
}
