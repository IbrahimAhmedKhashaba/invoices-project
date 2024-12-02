@extends('layouts.master')
@section('css')
<!--Internal  Font Awesome -->
<link href="{{URL::asset('assets/plugins/fontawesome-free/css/all.min.css')}}" rel="stylesheet">
<!--Internal  treeview -->
<link href="{{URL::asset('assets/plugins/treeview/treeview-rtl.css')}}" rel="stylesheet" type="text/css" />
@section('title')
    اضافة الصلاحيات - برنامج الفواتير
@stop
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الصلاحيات</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ اضافة نوع مستخدم</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('roles.store') }}" method="post" autocomplete="off">
        {{ csrf_field() }}
        <!-- row -->
        <div class="row">
            <div class="col-md-12">
                <div class="card mg-b-20">
                    <div class="card-body">
                        <div class="main-content-label mg-b-5">
                            <div class="col-xs-7 col-sm-7 col-md-7">
                                <div class="form-group">
                                    <p>اسم الصلاحية :</p>
                                    <input type="text" class="form-control" id="name" name="name"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <!-- col -->
                            <div class="col-lg-4">
                                <ul id="treeview1">
                                    <li>
                                        <a href="#">الصلاحيات</a>
                                        <ul>
                                            @foreach($permission as $value)
                                                <div class="form-check">
                                                    <div class="d-flex">
                                                        <input class="form-check-input mt-2" type="checkbox" value="{{ $value->name }}" id="flexCheckDefault" name="permission[]"/>
                                                        <label class="form-check-label mx-3">{{ $value->name }}</label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                            <!-- /col -->
                            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                <button type="submit" class="btn btn-main-primary">تاكيد</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- row closed -->
    </div>
    <!-- Container closed -->
    </div>
    <!-- main-content closed -->
    </form>
@endsection
@section('js')
<!-- Internal Treeview js -->
<script src="{{URL::asset('assets/plugins/treeview/treeview.js')}}"></script>
@endsection
