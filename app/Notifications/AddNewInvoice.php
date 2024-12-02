<?php

namespace App\Notifications;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class AddNewInvoice extends Notification
{
    use Queueable;

    private $invoice;
    public function __construct(Invoice $invoice)
    {
        //
        $this->invoice = $invoice;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            // 'data' => $this->invoice['body'],
            'id' => $this->invoice->id,
            'title' => 'تم إضافة فاتورة جديدة بواسطة',
            'user' => Auth::user()->name,
        ];
    }
}
