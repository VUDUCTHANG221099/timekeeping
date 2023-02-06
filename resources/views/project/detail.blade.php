@extends('layouts.master')
@section('title')
    Chi tiết dự án
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('project.get.all') }}">Dự án</a>
    </li>
    <li class="breadcrumb-item active">@yield('title')</li>
@endsection
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset("$base_url/listbox/icon_font/css/icon_font.css") }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset("$base_url/listbox/css/jquery.transfer.css?v=0.0.3") }}" />
    <link rel="stylesheet" href="https://unpkg.com/flatpickr@4.6.9/dist/plugins/monthSelect/style.css">
    <style>
        .transfer-demo {
            width: 640px;
            height: 200px;
            margin: 0 auto;
        }

        .btn-sub {
            width: 30px !important;
            height: 30px !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .h-3 {
            height: 111px;
        }

        .h-2 {
            height: 111px;
        }
    </style>
@endsection
@section('content')
    <div class="card text-center">
        <div class="card-header">
            <h2>
                {{ $getProject->name }}
            </h2>
        </div>
        <span class="text-success lh-base flashMessage">{{ session()->get('success') ?? false }}</span>
        <span class="text-danger lh-base danger_hide">{{ session()->get('error') ?? false }}</span>
        <div class="card-body w-100 " style="font-size:18px">
            <table class="table table-bordered">
                <tr>
                    <td>Trạng thái</td>
                    <td> @php
                        $status = $getProject->status;
                    @endphp
                        @switch($status)
                            @case(1)
                                <span class="rounded bg-primary p-2" style="font-size: 1rem">Open</span>
                            @break

                            @case(0)
                                <span class="rounded bg-warning p-2" style="font-size: 1rem">Pending</span>
                            @break

                            @case(2)
                                <span class="rounded bg-danger p-2" style="font-size: 1rem">Closed</span>
                            @break
                        @endswitch
                    </td>
                </tr>
                <tr>
                    <td>Mô tả</td>
                    <td>
                        <p style="font-size: 14px; text-align: justify">
                            {!! !empty($getProject->description) ? $getProject->description : '<h3>Chưa cập nhật Mô tả</h3>' !!}
                        </p>
                    </td>
                </tr>
                @if (count($getProject->users) > 0)
                    <tr>
                        <td>Nhân viên đã từng tham gia</td>
                        <td>
                            @foreach ($getProject->users->unique('name') as $employee)
                                @php
                                    $yearAndMonth = $carbon::parse($employee->day_addEmp)->format('Y-m');
                                    $yearAndMonthCurrent = $carbon::now()->format('Y-m');
                                @endphp
                                @if ($yearAndMonth < $yearAndMonthCurrent)
                                    <a style=" pointer-events: none;"
                                        href="{{ route('employee.get.detail', ['id' => $employee->id]) }}">
                                        <div class="badge bg-secondary  float-left"
                                            style="width: 8rem;  margin: 2px;
                                            padding: 10px">
                                            <span>
                                                {{ $employee->name }} <br>
                                                {{ $carbon::parse($employee->day_addEmp)->format('Y/m/d') }}
                                            </span>
                                        </div>
                                    </a>
                                @endif
                            @endforeach
                        </td>
                    </tr>
                @endif
                <tr>
                    <td>Tháng</td>
                    <td>
                        <div class="d-flex justify-content-center">
                            <input id="month_flatpickr" name="month" value="today" placeholder="Select Date.."
                                class="form-control" type="text" style="display: inline !important;width:200px" />
                            <a class="input-button"data-toggle="collapse" href="#month_flatpickr" role="button"
                                aria-expanded="false" aria-controls="month_flatpickr" style="margin: 7px 5px 0px -20px">
                                <i class="fas fa-calendar-alt"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Số người tham gia</td>
                    <td id="countEmployeeJoinProject"></td>
                </tr>
                <tr>
                    <td id="dsntgda">Danh sách nhân viên tham gia</td>
                    <td>
                        <div style="width:100%;overflow-y:auto" id="h-employee">
                            <table id="listEmployee" width="100%">
                            </table>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="float-right mb-3">
            <a href="{{ route('projectListEmployees', ['id' => $getProject->id, 'name' => $getProject->name]) }}"><button
                    class="btn btn-primary">
                    Phân công nhân viên vào dự án
                </button>
            </a>
        </div>
    </div>
    {{-- <h3 style="text-align:center">Danh sách nhân viên</h3>
<form action="{{ route('project.post.addEmployee') }}" method="post">
    @csrf
    <div id="transfer1" class="transfer-demo"></div>
    <input type="hidden" value="{{ $getProject->id }}" name="idProject">
</form>
<span class="d-none" id="apiEmp" data-apiListEmployee="{{ route('apiListEmployee') }}"></span> --}}
    <span class="d-none" id="idProject">{{ $getProject->id }}</span>
    <span class="d-none" id="apiEmployeeAndProjectCount">{{ route('apiEmployeeAndProjectCount') }}</span>
    <span class="d-none" id="apiRemoveEmployee">{{ route('apiRemoveEmployeePlan') }}</span>
@endsection
@push('scripts')
    <script src="https://unpkg.com/flatpickr@4.6.9/dist/plugins/monthSelect/index.js"></script>
    <script src="{{ asset("$base_url/js/project.js") }}"></script>
@endpush
