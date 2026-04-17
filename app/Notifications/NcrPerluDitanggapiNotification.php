<?php

namespace App\Notifications;

use App\Models\Ncr;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NcrPerluDitanggapiNotification extends Notification
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
            'title' => 'NCR perlu ditanggapi',
            'message' => 'NCR ' . $this->ncr->nomor_ncr . ' perlu segera ditanggapi.',
            'nomor_ncr' => $this->ncr->nomor_ncr,
            'url' => route('ncr.show', $this->ncr->nomor_ncr),
            'type' => 'ncr_tanggapi',
        ];
    }

    // public function toMail(object $notifiable): MailMessage
    // {
    //     return (new MailMessage)
    //         ->subject('NCR Perlu Ditanggapi')
    //         ->greeting('Halo ' . $notifiable->name . ',')
    //         ->line('Terdapat NCR yang perlu segera ditanggapi.')
    //         ->line('Nomor NCR: ' . $this->ncr->nomor_ncr)
    //         ->action('Lihat NCR', route('ncr.show', $this->ncr->nomor_ncr))
    //         ->line('Segera lakukan tindak lanjut.');
    // }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('NCR Perlu Ditanggapi')
            ->view('emails.perlu-ditanggapi', [
                'notifiable' => $notifiable,
                'ncr' => $this->ncr,
                'url' => route('ncr.show', $this->ncr->nomor_ncr),
            ]);
    }

}
