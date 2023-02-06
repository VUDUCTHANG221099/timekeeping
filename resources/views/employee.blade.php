{{-- @dd($employee[0]->projects) --}}
@extends('layouts.master')
@section('title', 'Nhân viên')
@section('breadcrumb')
    {{-- <li class="breadcrumb-item"><a href="{{ route('dashboard.get') }}">Dashboard</a></li> --}}
    <li class="breadcrumb-item active">@yield('title')</li>
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset("$base_url/css/media.css") }}">
@endpush
@section('content')
    <div class="container sticky">
        <form class="form-group d-flex ">
            <input class="form-control" type="text" id="keyword" placeholder="Search" autofocus>
            <button class="btn btn-outline-success bg-info" type="submit">Search</button>
        </form>
    </div>
    <div class="container d-flex" style="justify-content: space-between">
        <div class=" align-middle m-3">
            <span class="text-success flashMessage">{{ session()->get('success') ?? false }}</span>
        </div>
        <div>
            <a href="{{ route('employee.get.viewCreate') }}" class="btn btn-primary  mt-2 mb-4">
                <i class="fa fa-user-plus" aria-hidden="true"></i>
            </a>
        </div>
    </div>
    @php
        $arr = [];
        $countEmployy = count($employee);
        $sumArr = 0;
        $total = 0;
        $newCountEmployy = 0;
        if ($countEmployy % 4 != 0) {
            if ($countEmployy < 4) {
                $arr[0]['number'] = $countEmployy;
            }
            for ($i = 1; $i < $countEmployy; $i++) {
                if (($countEmployy - $i) % 4 == 0) {
                    $total += $i;
                    $newCountEmployy = $countEmployy - $i;
                    break;
                }
            }
        }
        $divide = 1;
        if ($newCountEmployy == 0) {
            $divide = $countEmployy / 4;
        } else {
            $divide = $newCountEmployy / 4;
        }
        if ($countEmployy >= 4) {
            for ($i = 0; $i < $divide; $i++) {
                $arr[$i]['number'] = 4;
            }
            for ($i = 0; $i < count($arr); $i++) {
                $sumArr += array_sum($arr[$i]);
            }
            $arr[count($arr)]['number'] = $total;
        }
        $count = 0;
    @endphp
    <div class="container">
        <div class="float-right mb-3">
            <button class="btn btn-primary" id="active-employee">Active</button>
            <button class="btn btn-secondary" id="inactivity-employee">Nghỉ việc</button>
        </div>
        <table class="table" border="1px">
            <thead class="table-primary ">
                <tr>
                    <th class="text-center align-middle" scope="col" style="width: 5%;">Mã nhân viên</th>
                    <th class="text-center align-middle"scope="col" style="width: 15%;">Tên nhân viên</th>
                    <th class="text-center align-middle" scope="col" style="width: 11%;">Vai trò</th>
                    <th class="text-center align-middle" scope="col" style="width: 27%;">
                        Dự án đang tham gia</th>
                    <th class="text-center align-middle" scope="col" style="width: 12%;">
                        Ngày checkout gần nhất</th>
                    <th class="text-center align-middle" scope="col" style="width: 22%;">Hành động</th>
                </tr>
            </thead>
            <!--Employee status 1-->
            <tbody id="active-employee-view">
                @if (count($employee) > 0)
                    @foreach ($employee as $item)
                        @if ($item->status == '1')
                            <tr class="search">
                                <td class="align-middle text-center">{{ $item->tabletUser->email }}</td>
                                <td class="align-middle">{{ $item->name }}</td>
                                <td class="align-middle  text-center">
                                    {{ $item->roles[0]->name == 'user' ? 'Nhân viên' : 'Quản trị viên' }}</td>
                                @if (isset($item->projects[0]))
                                    <td class="align-middle text-center ">
                                        @foreach ($item->projects->unique('name') as $project)
                                            @if ($project->status == '1')
                                                <!--Get month Current and year Current-->
                                                @php
                                                    $month = $carbon::parse($project->day_addEmp)->format('m');
                                                    $year = $carbon::parse($project->day_addEmp)->format('Y');
                                                    $monthCurrent = $carbon::now()->month;
                                                    $yearCurrent = $carbon::now()->year;
                                                @endphp
                                                <!--Get month Current and year Current-->
                                                @if ($month == $monthCurrent && ($year == $yearCurrent || $year < $yearCurrent))
                                                    <a
                                                        href="{{ route('project.get.detail', ['id' => $project->project_id]) }}">
                                                        <div class="badge bg-primary  float-left"
                                                            style="width: 6rem; height: 40px; margin: 2px; white-space: nowrap !important; overflow: hidden;  text-overflow: ellipsis;">
                                                            <span title="{{ $project->name }}" style="line-height: 40px;">
                                                                {{ $project->name }}
                                                            </span>
                                                        </div>
                                                    </a>
                                                @else
                                                    <a href="javascript:void(0)" style="cursor: default">
                                                        <div class="badge bg-secondary  float-left"
                                                            style="width: 6rem; height: 40px; margin: 2px; white-space: nowrap !important;
                                                            overflow: hidden;  text-overflow: ellipsis;">
                                                            <span title="{{ $project->name }}" style="line-height: 40px;">
                                                                {{ $project->name }}
                                                            </span>
                                                        </div>
                                                    </a>
                                                @endif
                                            @endif
                                        @endforeach
                                    </td>
                                @else
                                    <td></td>
                                @endif
                                @if (isset($item->employeeProjects[0]))
                                    <td class="align-middle text-center ">
                                        {{ $carbon::parse($item->employeeProjects[0]->day_work)->format('Y/m/d') }}
                                    </td>
                                @else
                                    <td></td>
                                @endif
                                <!--Active-->
                                <td class="align-middle">
                                    <a href="{{ route('employee.get.detail', ['id' => $item->id]) }}"
                                        class="btn btn-primary m-1">Xem
                                        chi tiết</a>
                                    <a href="{{ route('employee.get.viewUpdate', ['id' => $item->id]) }}"
                                        class="btn btn-primary m-1">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    @if (!in_array($item->id, $checkUserIsLogin))
                                        <a href="{{ route('employee.destroy', ['id' => $item->id]) }}"
                                            onclick="return confirm('Bạn có muốn xóa không ?')" class="btn btn-danger m-1">
                                            <i class="fa fa-trash "></i>
                                        </a>
                                    @endif
                                </td>
                                <!--Active-->
                            </tr>
                        @endif
                    @endforeach
                @endif
            </tbody>
            <!--Employee status 1-->
            <!--Employee status 0-->
            <tbody id="inactivity-employee-view">
                @if (count($employee) > 0)
                    @foreach ($employee as $item)
                        @if ($item->status == '0')
                            <tr class="search">
                                <td class="align-middle text-center">{{ $item->tabletUser->email }}</td>
                                <td class="align-middle">{{ $item->name }}</td>
                                <td class="align-middle  text-center">
                                    {{ $item->roles[0]->name == 'user' ? 'Nhân viên' : 'Quản trị viên' }}</td>
                                @if (isset($item->projects[0]))
                                    <td class="align-middle text-center ">
                                        @foreach ($item->projects->unique('name') as $project)
                                            @if ($project->status == '1')
                                                <!--Get month Current and year Current-->
                                                @php
                                                    $month = $carbon::parse($project->day_addEmp)->format('m');
                                                    $year = $carbon::parse($project->day_addEmp)->format('Y');
                                                    $monthCurrent = $carbon::now()->month;
                                                    $yearCurrent = $carbon::now()->year;
                                                @endphp
                                                <!--Get month Current and year Current-->
                                                <a style=" pointer-events: none;"
                                                    href="{{ route('project.get.detail', ['id' => $project->project_id]) }}">
                                                    <div class="badge bg-secondary  float-left"
                                                        style="width: 6rem; height: 40px; margin: 2px; white-space: nowrap !important; overflow: hidden;  text-overflow: ellipsis;">
                                                        <span title="{{ $project->name }}" style="line-height: 40px;">
                                                            {{ $project->name }}
                                                        </span>
                                                    </div>
                                                </a>
                                            @endif
                                        @endforeach
                                    </td>
                                @else
                                    <td></td>
                                @endif
                                @if (isset($item->employeeProjects[0]))
                                    <td class="align-middle text-center ">
                                        {{ $carbon::parse($item->employeeProjects[0]->day_work)->format('Y/m/d') }}
                                    </td>
                                @else
                                    <td></td>
                                @endif
                                <!--Active-->
                                <td class="align-middle">
                                    <a href="{{ route('employee.get.detail', ['id' => $item->id]) }}"
                                        class="btn btn-primary m-1">Xem
                                        chi tiết</a>
                                    <a href="{{ route('employee.get.viewUpdate', ['id' => $item->id]) }}"
                                        class="btn btn-primary m-1">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    @if (!in_array($item->id, $checkUserIsLogin))
                                        <a href="{{ route('employee.destroy', ['id' => $item->id]) }}"
                                            onclick="return confirm('Bạn có muốn xóa không ?')" class="btn btn-danger m-1">
                                            <i class="fa fa-trash "></i>
                                        </a>
                                    @endif
                                </td>
                                <!--Active-->
                            </tr>
                        @endif
                    @endforeach
                @endif
            </tbody>
            <!--Employee status 0-->
        </table>
    </div>
@endsection
@push('scripts')
    <script>
        $('#active-employee-view').show();
        $('#inactivity-employee-view').hide();
        $('#active-employee').attr('class', 'active btn btn-primary');
        $('#active-employee').click(function(e) {
            $('#active-employee').attr('class', 'active btn btn-primary');
            $('#inactivity-employee').attr('class', ' btn btn-secondary');
            $('#active-employee-view').show();
            $('#inactivity-employee-view').hide();
        });
        $('#inactivity-employee').click(function(e) {
            $('#inactivity-employee-view').show();
            $('#active-employee-view').hide();
            $('#active-employee').attr('class', 'btn btn-secondary');
            $('#inactivity-employee').attr('class', 'active btn btn-primary');
        })
    </script>
    <script src="{{ asset("$base_url/js/search.js") }}"></script>
@endpush
