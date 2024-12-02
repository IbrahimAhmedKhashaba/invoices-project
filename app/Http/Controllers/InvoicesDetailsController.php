<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoicesAttachments;
use App\Models\InvoicesDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InvoicesDetailsController extends Controller
{

    function __construct()
    {

    $this->middleware('permission:حذف المرفق', ['only' => ['delete_file']]);

    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
        //
    }

    public function get_file($invoice_number,$file_name)

    {
        $path = Storage::disk('public_uploads')->path($invoice_number . '/' . $file_name);
        return response()->download($path);
    }



    public function open_file($invoice_number,$file_name)

    {
        $path = Storage::disk('public_uploads')->path($invoice_number . '/' . $file_name);
        return response()->file($path);
    }

    public function delete_file(Request $request){
        $invoices = InvoicesAttachments::findOrFail($request->id_file);
        $invoices->delete();
        Storage::disk('public_uploads')->delete($request->invoice_number.'/'.$request->file_name);
        session()->flash('delete', 'تم حذف المرفق بنجاح');
        return back();
    }
}
