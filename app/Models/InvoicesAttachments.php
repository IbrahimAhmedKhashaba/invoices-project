<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoicesAttachments extends Model
{
    //
    protected $guarded = [];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
