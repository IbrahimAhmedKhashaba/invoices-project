<?php

namespace App\Http\Controllers;

use App\Exports\InvoicesExport;
use App\Jobs\SendAddingInvoiceNotification;
use App\Models\Invoice;
use App\Models\Notification as NotificationModel;
use App\Models\InvoicesAttachments;
use App\Models\InvoicesDetails;
use App\Models\Product;
use App\Models\Section;
use App\Models\User;
use App\Notifications\AddNewInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class InvoiceController extends Controller
{


    function __construct()
{

$this->middleware('permission:قائمة الفواتير', ['only' => ['index']]);
$this->middleware('permission:الفواتير المدفوعة', ['only' => ['paid_invoices']]);
$this->middleware('permission:الفواتير المدفوعة جزئيا', ['only' => ['partial_invoices']]);
$this->middleware('permission:الفواتير الغير مدفوعة', ['only' => ['unpaid_invoices']]);
$this->middleware('permission:اضافة فاتورة', ['only' => ['create','store']]);
$this->middleware('permission:تعديل الفاتورة', ['only' => ['edit','update']]);
$this->middleware('permission:تصدير EXCEL', ['only' => ['export']]);
$this->middleware('permission:طباعةالفاتورة', ['only' => ['print']]);
$this->middleware('permission:حذف الفاتورة', ['only' => ['destroy']]);
$this->middleware('permission:تغير حالة الدفع', ['only' => ['status_show','status_update']]);
$this->middleware('permission:ارشفة الفاتورة', ['only' => ['archive']]);
}


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = Invoice::with('section')->get();
        return view('invoices.invoices' , compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $sections = Section::all();
        $products = Product::all();
        return view('invoices.add_invoice' , compact(['sections' , 'products']));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        Invoice::create([
            'invoice_number' => $request->invoice_number,
            'invoice_date' => $request->invoice_Date,
            'due_date' => $request->Due_date,
            'product' => $request->product,
            'section_id' => $request->Section,
            'amount_collection' => $request->Amount_collection,
            'amount_commission' => $request->Amount_Commission,
            'discount' => $request->Discount,
            'value_vat' => $request->Value_VAT,
            'rate_vat' => $request->Rate_VAT,
            'total' => $request->Total,
            'status' => 'غير مدفوعة',
            'value_status' => 2,
            'note' => $request->note,
        ]);

        $invoice_id = Invoice::latest()->first()->id;
        InvoicesDetails::create([
            'invoice_id' => $invoice_id,
            'invoice_number' => $request->invoice_number,
            'product' => $request->product,
            'section' => $request->Section,
            'status' => 'غير مدفوعة',
            'value_status' => 2,
            'note' => $request->note,
            'user' => (Auth::user()->name),
        ]);


        if ($request->hasFile('pic')) {

            $invoice_id = Invoice::latest()->first()->id;
            $image = $request->file('pic');
            $file_name = $image->getClientOriginalName();
            $invoice_number = $request->invoice_number;

            $attachments = new InvoicesAttachments();
            $attachments->file_name = $file_name;
            $attachments->invoice_number = $invoice_number;
            $attachments->Created_by = Auth::user()->name;
            $attachments->invoice_id = $invoice_id;
            $attachments->save();

            // move pic
            $imageName = $request->pic->getClientOriginalName();
            $request->pic->move(public_path('Attachments/' . $invoice_number), $imageName);
        }

        $users = User::where('id' , '!=' , Auth::user()->id)->get();
        $invoice = Invoice::latest()->first();
        Notification::send($users, new AddNewInvoice($invoice));

        // $user->notify(new AddNewInvoice($invoice));
        // SendAddingInvoiceNotification::dispatch($users , $invoice);

        session()->flash('Add', 'تم اضافة الفاتورة بنجاح');
        return redirect('/invoices');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $invoice = Invoice::with('section')->with('invoices_details')->with('invoices_attachments')->find($id);
        return view('invoices.details_invoice', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
        $invoice = Invoice::with('section')->with('invoices_details')->with('invoices_attachments')->find($id);
        $sections = Section::all();
        $products = Product::all();
        return view('invoices.edit_invoice' , compact(['invoice' , 'sections' , 'products']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        //
        $invoice = Invoice::find($request->invoice_id);
        $invoice->update([
            'invoice_number' => $request->invoice_number,
            'invoice_date' => $request->invoice_Date,
            'due_date' => $request->Due_date,
            'product' => $request->product,
            'section_id' => $request->Section,
            'amount_collection' => $request->Amount_collection,
            'amount_commission' => $request->Amount_Commission,
            'discount' => $request->Discount,
            'value_vat' => $request->Value_VAT,
            'rate_vat' => $request->Rate_VAT,
            'total' => $request->Total,
            'note' => $request->note,
        ]);

        session()->flash('edit', 'تم تعديل الفاتورة بنجاح');
        return redirect('/invoices');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {

        $invoice = Invoice::withTrashed()->find($request->invoice_id);

        $attachment = InvoicesAttachments::select('invoice_number')->where('invoice_id', $request->invoice_id)->first();

        if(!empty($attachment)){
            Storage::disk('public_uploads')->deleteDirectory($attachment->invoice_number);
        }

        $notifications = NotificationModel::where('data', 'like', '%"id":' . $invoice->id . '%')->get();
        foreach ($notifications as $notification) {
            $notification->delete();
        }


        $invoice->forceDelete();
        session()->flash('delete' , 'تم حذف الفاتورة بنجاح');
        return redirect('/invoices');
    }

    public function getProducts($id){
        $products = Product::where('section_id' , $id)->pluck('product_name' , 'id');
        return json_decode($products);
    }

    public function status_show($id){
        $invoice = Invoice::with('section')->with('invoices_details')->with('invoices_attachments')->find($id);
        return view('invoices.status_update' , compact('invoice'));
    }

    public function status_update(Request $request){
        $invoice = Invoice::find($request->invoice_id);
        if($request->status == 'مدفوعة جزئيا'){
            $value_status = 1;
        } else if($request->status == 'مدفوعة'){
            $value_status = 0;
        }
        $invoice->update([
            'status'=>$request->status,
            'value_status' => $value_status
        ]);

        InvoicesDetails::create([
            'invoice_id' => $request->invoice_id,
            'invoice_number' => $request->invoice_number,
            'product' => $request->product,
            'section' => $request->section,
            'status' => $request->status,
            'value_status' => $value_status,
            'note' => $request->note,
            'user' => (Auth::user()->name),
        ]);

        session()->flash('edit', 'تم تعديل حالة الفاتورة بنجاح');

        return redirect('/invoices');

    }

    public function paid_invoices(){
        $invoices = Invoice::where('value_status', 0)->get();
        return view('invoices.invoices_paid', compact('invoices'));
    }

    public function unPaid_invoices(){
        $invoices = Invoice::where('value_status', 2)->get();
        return view('invoices.invoices_unPaid', compact('invoices'));
    }

    public function partial_invoices(){
        $invoices = Invoice::where('value_status', 1)->get();
        return view('invoices.invoices_partial', compact('invoices'));
    }

    public function archive(Request $request){
        $invoice = Invoice::find($request->invoice_id);

        $invoice->delete();
        session()->flash('archive', 'تم نقل الفاتورة إلى الأرشيف بنجاح');
        return redirect()->back();
    }

    public function print($id){
        $invoice = Invoice::with('section')->find($id);
        return view('invoices.print_invoice', compact('invoice'));
    }

    public function export()
    {
        return Excel::download(new InvoicesExport, 'invoices.xlsx');
    }
}
