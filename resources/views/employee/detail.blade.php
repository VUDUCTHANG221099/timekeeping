@extends('layouts.master')
<style>
    .form-control {
        display: inline !important;
        width: 90% !important;
    }
</style>
@section('title')
    Chi tiết nhân viên
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('employee.get.all') }}">Nhân viên</a></li>
    <li class="breadcrumb-item active">@yield('title')</li>
@endsection
@push('css')
    <style>
        .title {
            font-size: 24px;
            text-align: center;
            vertical-align: center;
        }

        .content {
            font-size: 24px;
        }

        .lh-height {
            height: 190px;
        }

        .lh-height-1 {
            height: 105px;
        }

        .lh-height-2 {
            height: 105px;
        }

        .lh-default {
            height: 60px;
        }

        .align-middle {
            line-height: 22px !important;
        }
    </style>
    <link rel="stylesheet" href="https://unpkg.com/flatpickr@4.6.9/dist/plugins/monthSelect/style.css">
@endpush
@section('content')
    <div class="card text-center">
        <div class="card-header">
            <h2>
                <span class="text-success">#{{ $emp_code->email }}</span>
                <span id="getName">{{ $employee->name }}</span>
            </h2>
        </div>
        <span class="text-success lh-base flashMessage">{{ session()->get('success') ?? false }}</span>
        <span class="text-danger lh-base danger_hide">{{ session()->get('error') ?? false }}</span>
        <div class="card-body w-100 " style="display:flex; font-size:25px; height: auto">
            <table class="table table-bordered">
                <tr>
                    <td class="title">Email</td>
                    <td class="content"> <a href="mailto:{{ $employee->email }}">{{ $employee->email }}</a></td>
                </tr>
                <tr>
                    <td class="title">Vai trò</td>
                    <td class="content"> {{ $employee->roles[0]->display_name }}</td>
                </tr>
                <tr>
                    <td class="title" id="cacDuAn">Các dự án</td>
                    <td>
                        <form action="{{ route('employee.put.AddPercent') }}" method="POST">
                            <span id="idEmployee" class="d-none" data-idEmployee="{{ $employee->id }}"></span>
                            <input type="hidden" name="id" value="{{ $employee->id }}" name="id">
                            @csrf
                            @method('put')
                            <div class="d-flex justify-content-center mb-3">
                                <input id="month_flatpickr" name="month" value="today" placeholder="Select Date.."
                                    class="form-control w-25" type="text" style="display: inline !important;" />
                                <a class="input-button"data-toggle="collapse" href="#month_flatpickr" role="button"
                                    aria-expanded="false" aria-controls="month_flatpickr" style="margin: 9px 5px 0px -20px">
                                    <i class="fas fa-calendar-alt"></i>
                                </a>
                            </div>
                            <center>
                                <div id="projectAndPercent" style="overflow-x: auto;width: 550px;">
                                    <table class="w-100" id="listProjectEmployee">
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </center>
                            <div id="btn-submit" class="d-flex justify-content-center"></div>
                        </form>
                    </td>
                </tr>
            </table>
        </div>
        <div class="w-100 " style="display:flex; font-size:25px; height: auto">
            <table class="table">
                <tr>
                    <td class="title" id="cacDuAn" style="width: 20%"></td>
                    <td>
                        <center>
                            <div class="container-full mt-3 mb-3"
                                style="display: flex;justify-content: center; position: sticky; top:0" id="export-btn">
                            </div>
                            <div class="d-flex justify-content-center" style="width: 550px;">
                                <table id="listProjectMonth" class="w-100 table table-bordered">
                                </table>
                            </div>
                        </center>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <span class="d-none" id="listPlanOfEmployee" data-listPlanOfEmployee="{{ route('apilistPlanOfEmployee') }}"></span>
    <span class="d-none" id="listProjectOfEmployeeMonth">{{ route('apilistProjectOfEmployeeMonth') }}</span>
    <span class="d-none" id="exportOneReportInAdmin">{{ route('exportOneReportInAdmin') }}</span>
    <span class="d-none" id="exportPDF">{{ route('exportPDF') }}</span>
@endsection
@push('scripts')
    <script src="https://unpkg.com/flatpickr@4.6.9/dist/plugins/monthSelect/index.js"></script>
    <script src="{{ asset("$base_url/excel/table2excel.js") }}"></script>
    <script src="{{ asset("$base_url/js/employee.js") }}"></script>
@endpush
