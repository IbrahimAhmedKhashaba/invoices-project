<?php

namespace App\Exports;

use App\Models\Invoice;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class InvoicesExport implements FromCollection,  WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Invoice::select('invoice_number', 'invoice_date', 'due_date','section_id', 'product', 'amount_collection','amount_commission', 'rate_vat', 'value_vat','total', 'status', 'payment_date','note')->get();
    }

    public function headings(): array
    {
        return [
            'Invoice Number',
            'Invoice Date',
            'Due Date',
            'Section ID',
            'Product',
            'Amount Collection',
            'Amount Commission',
            'Rate VAT',
            'Value VAT',
            'Total',
            'Status',
            'Payment Date',
            'Note',
        ];
    }
}
