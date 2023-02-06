@extends('layouts.master')
@section('title', 'Tổng hợp')
@section('breadcrumb')
    <li class="breadcrumb-item active">@yield('title')</li>
@endsection
@push('css')
    <link rel="stylesheet" href="https://unpkg.com/flatpickr@4.6.9/dist/plugins/monthSelect/style.css">
@endpush
@section('content')
    <div class="container-full">
        <!--TODO: Calendar-->
        <div class="d-flex justify-content-center mb-3">
            <input id="month_flatpickr" name="month" value="today" placeholder="Select Date.." class="form-control"
                type="text" style="display: inline !important;width: 18%" />
            <a class="input-button"data-toggle="collapse" href="#month_flatpickr" role="button" aria-expanded="false"
                aria-controls="month_flatpickr" style="margin: 9px 5px 0px -20px">
                <i class="fas fa-calendar-alt"></i>
            </a>
        </div>
        <!--TODO: Calendar-->
        <!--TODO schedule and reality-->
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
        <!--TODO schedule and reality-->
        <!--TODO Project And Employee To Month-->
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
        <!--TODO Project And Employee To Month-->
        <div class="container-full mt-3" style="display: flex;justify-content: center;" id="btnExport"></div>
    </div>
    <!--API-->
    <span class="d-none" id="APIScheduleAndReality">{{ route('apiScheduleAndReality') }}</span>
    <span class="d-none" id="APIProjectAndEmployeeToMonth">
        {{ route('apiProjectAndEmployeeToMonth') }}
    </span>
    {{-- <span class="d-none" id="exportAdmin">{{ route('exportAdmin') }}</span> --}}
    <!--API-->
@endsection
@push('scripts')
    <script src="https://unpkg.com/flatpickr@4.6.9/dist/plugins/monthSelect/index.js"></script>
    <script src="{{ asset("$base_url/js/adminReport.js") }}"></script>
@endpush
