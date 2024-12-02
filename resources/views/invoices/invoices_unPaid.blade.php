@extends('layouts.master')
@section('title')
قائمة الفواتير الغير مدفوعة - برنامج الفواتير
@stop

@section('css')
<!-- Internal Data table css -->
<link href="{{URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
@endsection<!--Internal   Notify -->
<link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto">الفواتير</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ قائمة الفواتير</span>
						</div>
					</div>
				</div>
				<!-- breadcrumb -->
@endsection
@section('content')

{{-- messages --}}

@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@if (session()->has('Add'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>{{ session()->get('Add') }}</strong>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

@if (session()->has('edit'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>{{ session()->get('edit') }}</strong>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

@if (session()->has('delete'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>{{ session()->get('delete') }}</strong>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

@if (session()->has('restore'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>{{ session()->get('restore') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
    </div>
@endif

@if (session()->has('archive'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>{{ session()->get('archive') }}</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
{{-- end messages --}}
				<!-- row -->
				<div class="row">
                        <!--div-->
                        <div class="col-xl-12">
                            <div class="card mg-b-20">
                                <div class="card-header pb-0">
                                    <div class="d-flex">
                                        @can('اضافة فاتورة')
                                        <div class="col-sm-6 col-md-4 col-xl-3">
                                            <a class="modal-effect btn btn-outline-primary btn-block" href="{{ route('invoices.create') }}">إضافة فاتورة</a>
                                        </div>
                                        @endcan
                                        @can('تصدير EXCEL')
                                        <div class="col-sm-6 col-md-4 col-xl-3">
                                            <a class="modal-effect btn btn-outline-primary btn-block" href="{{ route('export') }}"><i class="fas fa-file-download mx-2"></i>تصدير اكسيل</a>
                                        </div>
                                        @endcan
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="example1" class="table key-buttons text-md-nowrap" data-page-length = '50'>
                                            <thead>
                                                <tr>
                                                    <th class="border-bottom-0">#</th>
                                                    <th class="border-bottom-0">رقم الفاتورة</th>
                                                    <th class="border-bottom-0">تاريخ الفاتورة</th>
                                                    <th class="border-bottom-0">تاريخ الإستحقاق</th>
                                                    <th class="border-bottom-0">المنتج</th>
                                                    <th class="border-bottom-0">القسم</th>
                                                    <th class="border-bottom-0">الخصم</th>
                                                    <th class="border-bottom-0">نسبة الضريبة</th>
                                                    <th class="border-bottom-0">قيمة الضريبة</th>
                                                    <th class="border-bottom-0">الإجمالي</th>
                                                    <th class="border-bottom-0">الحالة</th>
                                                    <th class="border-bottom-0">ملاحظات</th>
                                                    <th class="border-bottom-0">العمليات</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i=0 ?>
                                                @foreach($invoices as $invoice)
                                                    <tr>
                                                        <?php $i++ ?>
                                                        <td>{{ $i }}</td>
                                                        <td>{{ $invoice->invoice_number }}</td>
                                                        <td>{{ $invoice->invoice_date }}</td>
                                                        <td>{{ $invoice->due_date }}</td>
                                                        <td>{{ $invoice->product }}</td>
                                                        <td>
                                                            <a href="{{ url('InvoicesDetails') }}/{{ $invoice->id }}">{{ $invoice->section->section_name }}</a>
                                                        </td>
                                                        <td>{{ $invoice->discount }}</td>
                                                        <td>{{ $invoice->rate_vat }}</td>
                                                        <td>{{ $invoice->value_vat }}</td>
                                                        <td>{{ $invoice->total }}</td>
                                                        <td>
                                                            @if ($invoice->value_status == 1)
                                                                <span class="text-warning">{{ $invoice->status }}</span>
                                                            @elseif($invoice->value_status == 2)
                                                                <span class="text-danger">{{ $invoice->status }}</span>
                                                            @else
                                                                <span class="text-success">{{ $invoice->status }}</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ $invoice->note }}</td>
                                                        <td>
                                                            <div class="dropdown">
                                                                <button aria-expanded="false" aria-haspopup="true"
                                                                    class="btn ripple btn-primary btn-sm" data-toggle="dropdown"
                                                                    type="button">العمليات<i class="fas fa-caret-down ml-1"></i></button>
                                                                    <div class="dropdown-menu tx-13">
                                                                        @can('تعديل الفاتورة')
                                                                            <a class="dropdown-item" href="{{route('invoices.edit', $invoice->id)}}">تعديل الفاتورة</a>
                                                                        @endcan
                                                                        @can('حذف الفاتورة')
                                                                            <a class="dropdown-item" href="#" data-invoice_id="{{ $invoice->id }}" data-toggle="modal" data-target="#delete_invoice"><i class="text-danger fas fa-trash-alt"></i>&nbsp;&nbsp;حذف الفاتورة</a>
                                                                        @endcan
                                                                        @can('تغير حالة الدفع')
                                                                            <a class="dropdown-item" href="{{ route('invoices.status_show', $invoice->id) }}"><i class=" text-success fas fa-money-bill"></i>&nbsp;&nbsp;تغير حالة الدفع</a>
                                                                        @endcan
                                                                        @can('ارشفة الفاتورة')
                                                                            <a class="dropdown-item" href="#" data-invoice_id="{{ $invoice->id }}" data-toggle="modal" data-target="#Transfer_invoice"><i class="text-warning fas fa-exchange-alt"></i>&nbsp;&nbsp; نقل إلى الارشيف</a>
                                                                        @endcan
                                                                        @can('طباعةالفاتورة')
                                                                            <a class="dropdown-item" href="{{ route('invoices.print', $invoice->id) }}">
                                                                                <i class="text-success fas fa-print"></i>&nbsp;&nbsp;طباعة الفاتورة
                                                                            </a>
                                                                        @endcan
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/div-->
				        </div>
				        <!-- row closed -->
                        <!-- حذف الفاتورة -->
                        <div class="modal fade" id="delete_invoice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">حذف الفاتورة</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <form action="{{ url('invoices/destroy') }}" method="post">
                                            {{ method_field('delete') }}
                                            {{ csrf_field() }}
                                        </div>
                                        <div class="modal-body">
                                            هل انت متاكد من عملية الحذف ؟
                                            <input type="hidden" name="invoice_id" id="invoice_id" value="">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                                            <button type="submit" class="btn btn-danger">تاكيد</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- ارشيف الفاتورة -->
                        <div class="modal fade" id="Transfer_invoice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">ارشفة الفاتورة</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <form action="{{ route('invoices.archive') }}" method="post">
                                            {{ method_field('delete') }}
                                            {{ csrf_field() }}
                                        </div>
                                        <div class="modal-body">
                                            هل انت متاكد من عملية الارشفة ؟
                                            <input type="hidden" name="invoice_id" id="invoice_id" value="">
                                            <input type="hidden" name="id_page" id="id_page" value="2">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                                            <button type="submit" class="btn btn-success">تاكيد</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
			    <!-- Container closed -->
		        </div>
		<!-- main-content closed -->
@endsection
@section('js')
<!-- Internal Data tables -->
<script src="{{URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/jquery.dataTables.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/jszip.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/pdfmake.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/vfs_fonts.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.html5.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.print.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js')}}"></script>
<!--Internal  Datatable js -->
<script src="{{URL::asset('assets/js/table-data.js')}}"></script>
    <!--Internal  Notify js -->
<script src="{{ URL::asset('assets/plugins/notify/js/notifIt.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/notify/js/notifit-custom.js') }}"></script>
<script>
    $('#delete_invoice').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)
        var invoice_id = button.data('invoice_id')
        var modal = $(this)
        modal.find('.modal-body #invoice_id').val(invoice_id);
    })
</script>
<script>
    $('#Transfer_invoice').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)
        var invoice_id = button.data('invoice_id')
        var modal = $(this)
        modal.find('.modal-body #invoice_id').val(invoice_id);
    })
</script>
@endsection
