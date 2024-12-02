<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Invoice extends Model
{
    //
    use SoftDeletes;
    use Notifiable;
    protected $fillable = [
        'invoice_number' ,
        'invoice_date' ,
        'due_date' ,
        'product',
        'section_id' ,
        'amount_collection' ,
        'amount_commission' ,
        'discount' ,
        'value_vat' ,
        'rate_vat' ,
        'total' ,
        'status' ,
        'value_status' ,
        'note'
    ];

    public function section(){
        return $this->belongsTo(Section::class);
    }


    public function invoices_details()
    {
        return $this->hasMany(InvoicesDetails::class);
    }

    public function invoices_attachments()
    {
        return $this->hasMany(InvoicesAttachments::class);
    }
}
