<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoicesDetails extends Model
{
    //
    protected $fillable = [
        'invoice_id' ,
        'invoice_number' ,
        'product',
        'section' ,
        'status' ,
        'value_status' ,
        'note' ,
        'user'
    ];

    protected $guarded = [];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
