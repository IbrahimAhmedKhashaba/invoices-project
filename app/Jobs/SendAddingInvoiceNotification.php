<?php

namespace App\Jobs;

use App\Models\Invoice;
use App\Models\User;
use App\Notifications\AddNewInvoice;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Notification;

class SendAddingInvoiceNotification implements ShouldQueue
{
    use Queueable;

    protected $users;
    protected $invoice;

    /**
     * Create a new job instance.
     */
    public function __construct($users , $invoice)
    {
        //
        $this->users = $users;
        $this->invoice = $invoice;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Notification::send($this->users, new AddNewInvoice($this->invoice));
        }
}
