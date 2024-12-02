<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    //

    public function mark_all_as_read(){
        auth()->user()->unreadNotifications->markAsRead();
        return redirect()->back();
    }

    public function mark_as_read($id, $invoice_id){
        $notification = auth()->user()->notifications()->find($id);
        $notification->markAsRead();
        return redirect('/InvoicesDetails/'.$invoice_id);
    }
}
