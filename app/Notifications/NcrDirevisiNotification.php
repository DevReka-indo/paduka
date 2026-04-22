<?php

namespace App\Notifications;

use App\Models\Ncr;
use App\Models\NcrChangeLog;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NcrDirevisiNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Ncr $ncr,
        public NcrChangeLog $changeLog
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'NCR direvisi',
            'message' => 'NCR ' . $this->ncr->nomor_ncr .
                ' telah direvisi oleh ' . ($this->changeLog->user?->name ?? 'Unknown User') .
                ' (' . $this->changeLog->revision . ').',
            'nomor_ncr' => $this->ncr->nomor_ncr,
            'revision' => $this->changeLog->revision,
            'revision_index' => $this->changeLog->revision_index,
            'action' => $this->changeLog->action,
            'changes' => $this->changeLog->changes,
            'edited_by' => $this->changeLog->user?->name,
            'edited_by_id' => $this->changeLog->user_id,
            'url' => route('ncr.revision.show', [
                'nomor_ncr' => $this->ncr->nomor_ncr,
                'rev' => $this->changeLog->revision_index,
            ]),
            'type' => 'ncr_revisi',
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('NCR Direvisi')
            ->view('emails.direvisi', [
                'notifiable' => $notifiable,
                'ncr' => $this->ncr,
                'changeLog' => $this->changeLog,
                'url' => route('ncr.revision.show', [
                    'nomor_ncr' => $this->ncr->nomor_ncr,
                    'rev' => $this->changeLog->revision_index,
                ]),
            ]);
    }
}
