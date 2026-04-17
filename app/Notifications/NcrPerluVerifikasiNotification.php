<?php

namespace App\Notifications;

use App\Models\Ncr;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NcrPerluVerifikasiNotification extends Notification
{
    use Queueable;

    public function __construct(public Ncr $ncr)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'NCR perlu diverifikasi',
            'message' => 'NCR ' . $this->ncr->nomor_ncr . ' telah ditanggapi dan perlu diverifikasi.',
            'nomor_ncr' => $this->ncr->nomor_ncr,
            'url' => route('ncr.show', $this->ncr->nomor_ncr),
            'type' => 'ncr_verifikasi',
        ];
    }

    // public function toMail(object $notifiable): MailMessage
    // {
    //     return (new MailMessage)
    //         ->subject('NCR Perlu Diverifikasi')
    //         ->greeting('Halo ' . ($notifiable->name ?? 'User') . ',')
    //         ->line('NCR berikut telah ditanggapi dan sekarang perlu diverifikasi.')
    //         ->line('Nomor NCR: ' . $this->ncr->nomor_ncr)
    //         ->action('Lihat NCR', route('ncr.show', $this->ncr->nomor_ncr))
    //         ->line('Silakan lakukan verifikasi pada NCR tersebut.');
    // }


    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('NCR Perlu Diverifikasi')
            ->view('emails.perlu-diverifikasi', [
                'notifiable' => $notifiable,
                'ncr' => $this->ncr,
                'url' => route('ncr.show', $this->ncr->nomor_ncr),
            ]);
    }

}
