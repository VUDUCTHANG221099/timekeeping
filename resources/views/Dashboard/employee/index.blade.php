@extends('layouts.master')
@section('title', 'Dashboard')
{{-- @section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection --}}
@push('css')
    <link rel="stylesheet" href="https://unpkg.com/flatpickr@4.6.9/dist/plugins/monthSelect/style.css">
@endpush
@section('content')
    <div class="container-full">
        <div class="d-flex justify-content-center mb-5">
            <input id="month_flatpickr" name="month" value="today" placeholder="Select Date.." class="form-control"
                type="text" style="display: inline !important;width:200px" />
            <a class="input-button"data-toggle="collapse" href="#month_flatpickr" role="button" aria-expanded="false"
                aria-controls="month_flatpickr" style="margin: 7px 5px 0px -20px">
                <i class="fas fa-calendar-alt"></i>
            </a>
        </div>
        <div class="mb-5">
            <table class="table table-striped bg-dark" style="width: 36%;" id="tablePlanReality">
                <thead>
                    <tr>
                        <th>Dự án</th>
                        <th>Kế hoạch</th>
                        <th>Thực tế</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <div style="overflow-x:auto;">
            <table class="table table-primary" id="tableShowMonth" border="1px">
                <thead>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="container-full mt-3" style="display: flex;justify-content: center;" id="btnExport"></div>
    <span id="listProjectMonth" class="d-none" data-listProject-month="{{ route('apiListMonthlyProject') }}"></span>
    <span id="planReality" class="d-none" data-planReality="{{ route('apiPlanAndReality') }}"></span>
    <span id="exportOneExcel" class="d-none" data-exportOneExcel="{{ route('exportOneReport') }}"></span>
    <span id="userId" class="d-none">{{ Auth::user()->id }}</span>
    {{-- <span id="totalReality" class="d-none" data-totalReality="{{ route('apitotalReality') }}"></span> --}}
    <span>@csrf</span>
@endsection
@push('scripts')
    <script src="https://unpkg.com/flatpickr@4.6.9/dist/plugins/monthSelect/index.js"></script>
    <script src="{{ asset("$base_url/excel/table2excel.js") }}"></script>
    <script src="{{ asset("$base_url/js/Dashboard/employee.js") }}"></script>
@endpush
