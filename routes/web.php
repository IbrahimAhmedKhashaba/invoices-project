<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomersReportController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceArchiveController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoicesAttachmentsController;
use App\Http\Controllers\InvoicesDetailsController;
use App\Http\Controllers\InvoicesReportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\UserController;
use App\Models\InvoicesDetails;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

// Auth::routes(['register' => false]);
Auth::routes();



Route::resource('invoices', InvoiceController::class);
Route::get('invoices_paid', [InvoiceController::class , 'paid_invoices'])->name('paid_invoices');
Route::get('invoices_unPaid', [InvoiceController::class , 'unPaid_invoices'])->name('unPaid_invoices');
Route::get('invoices_partial', [InvoiceController::class , 'partial_invoices'])->name('partial_invoices');
Route::get('invoices_archive', [InvoiceArchiveController::class , 'index'])->name('invoices_archive');
Route::patch('invoices_archive_update', [InvoiceArchiveController::class , 'restore'])->name('archive_restore');
Route::delete('invoices_archive_destroy', [InvoiceArchiveController::class , 'destroy'])->name('archive.destroy');
Route::delete('archive', [InvoiceController::class , 'archive'])->name('invoices.archive');
Route::get('print/{id}', [InvoiceController::class , 'print'])->name('invoices.print');
Route::resource('sections', SectionController::class);
Route::resource('products', ProductController::class);
Route::resource('invoicesAttachments', InvoicesAttachmentsController::class);
Route::get('/section/{id}' , [InvoiceController::class,'getProducts'])->name('getProducts');
Route::get('/InvoicesDetails/{id}' , [InvoiceController::class,'show']);
Route::get('/view_file/{invoice_number}/{file_name}' , [InvoicesDetailsController::class,'open_file']);
Route::get('/download/{invoice_number}/{file_name}' , [InvoicesDetailsController::class,'get_file']);
Route::delete('/delete' , [InvoicesDetailsController::class,'delete_file'])->name('attachment.delete');
Route::get('/status_show/{id}' , [InvoiceController::class,'status_show'])->name('invoices.status_show');
Route::post('/status_update' , [InvoiceController::class,'status_update'])->name('invoices.status_update');
// Route::post('/InvoiceAttachments/create' , [InvoicesDetailsController::class,'add_file']);
Route::get('/export', [InvoiceController::class, 'export'])->name('export');

Route::get('/invoices_report', [InvoicesReportController::class, 'index'])->name('invoices_report.index');
Route::post('/search_invoices', [InvoicesReportController::class, 'search_invoices'])->name('invoices_report.search_invoices');
Route::get('/customers_report', [CustomersReportController::class, 'index'])->name('invoices_report.index');
Route::post('/search_customers', [CustomersReportController::class, 'search_customers'])->name('invoices_report.search_customers');
Route::get('mark_all_as_read' , [NotificationController::class,'mark_all_as_read'])->name('mark_all_as_read');
Route::get('mark_as_read/{id}/{invoice_id}' , [NotificationController::class,'mark_as_read'])->name('mark_as_read');
Route::middleware(['auth'])->group(function () {
    Route::resource('roles' , RoleController::class);
    Route::resource('users' , UserController::class);
    Route::get('/home', [HomeController::class, 'index'])->name('home');
});


// Route::get('/{page}', [AdminController::class, 'index']);

