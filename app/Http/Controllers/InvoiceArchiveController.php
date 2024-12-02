<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceArchiveController extends Controller
{
    //

    function __construct()
{

$this->middleware('permission:ارشيف الفواتير', ['only' => ['index']]);
$this->middleware('permission:حذف الفاتورة', ['only' => ['destroy']]);

}

    public function index(){
        $invoices = Invoice::onlyTrashed()->get();
        return view('invoices.invoices_archive',compact('invoices'));
    }

    public function restore(Request $request){
        Invoice::withTrashed()->where('id' , $request->invoice_id)->restore();
        session()->flash('restore', 'تم استعادة الفاتورة من الأرشيف بنجاح');
        return redirect()->route('invoices_archive');
    }

    public function destroy(Request $request){
        Invoice::withTrashed()->where('id' , $request->invoice_id)->forceDelete();
        session()->flash('delete', 'تم حذف الفاتورة نهائيا من الأرشيف');
        return redirect()->route('invoices_archive');
    }
}
