@extends('layouts.master') @section('title', 'Dự án') @section('breadcrumb')
<li class="breadcrumb-item active">@yield('title')</li>
@endsection
@push('css')
<link rel="stylesheet" href="{{ asset("$base_url/css/media.css") }}">
@endpush
@section('content')
<div class="container sticky">
    <form class="form-group d-flex">
        <input class="form-control" type="text" id="keyword" placeholder="Search" autofocus>
        <button class="btn btn-outline-success bg-info" type="submit">Search</button>
    </form>
</div>
<div class="container d-flex" style="justify-content: space-between">
    <div class=" align-middle m-3">
        <span class="text-success flashMessage">{{ session()->get('success') ?? false }}</span>
    </div>
    <div>
        <a href="{{ route('project.get.viewCreate') }}" class="btn btn-primary float-right mt-2 mb-4">
            <i class="fa fa-plus" aria-hidden="true"></i>
        </a>
    </div>
</div>
@php
    $arr = [];
    $countEmployy = count($listProject);
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
    <div class="mb-3 float-right">
        <button class="btn btn-primary" id="active-Project">Active</button>
        <button class="btn btn-secondary" id="finished-Project">Finished</button>
    </div>
    <table class="table" border="1px">
        <thead class="table-primary">
            <tr>
                <th class="text-center align-middle" style="width: 5%;">STT</th>
                <th class="text-center align-middle" style="width: 10%;">Tên dự án</th>
                <th class="text-center align-middle" style="width: 20%;">Thành viên tham gia</th>
                <th class="text-center align-middle" style="width: 5%;">Số giờ</th>
                <th class="text-center align-middle" style="width: 10%;">Ngày bắt đầu</th>
                <th class="text-center align-middle" style="width: 10%;">Ngày dự kiến kết thúc</th>
                <th class="text-center align-middle" style="width: 18%;">Hành động</th>
            </tr>
        </thead>
        <!--Project status  1-->
        <tbody id="active-Project-view">
            @if (count($listProject) > 0)
                @foreach ($listProject as $project)
                    @if ($project->status == '1')
                        <tr class="search">
                            <td class="align-middle text-center">{{ ++$count }}</td>
                            <td class="align-middle text-center">{{ $project->name }}</td>
                            <td class="align-middle text-center">
                                @if ($project->users)
                                    @foreach ($project->users->unique('name') as $employee)
                                        <!--Get month Current and year Current-->
                                        @php
                                            $month = $carbon::parse($employee->day_addEmp)->format('m');
                                            $year = $carbon::parse($employee->day_addEmp)->format('Y');
                                            $monthCurrent = $carbon::now()->month;
                                            $yearCurrent = $carbon::now()->year;
                                        @endphp
                                        <!--Get month Current and year Current-->
                                        @if ($employee->status == '1')
                                            @if ($month == $monthCurrent && ($year == $yearCurrent || $year < $yearCurrent))
                                                <a
                                                    href="{{ route('employee.get.detail', ['id' => $employee->user_id]) }}">
                                                    <div class="badge bg-primary  float-left"
                                                        style="width: 6rem; height: 40px; margin: 2px; white-space: nowrap !important; overflow: hidden;  text-overflow: ellipsis;">
                                                        <span title="{{ $employee->name }}" style="line-height: 40px;">
                                                            {{ $employee->name }}
                                                        </span>
                                                    </div>
                                                </a>
                                            @else
                                                <a style=" cursor: default;" href="javascript:void(0)">
                                                    <div class="badge bg-secondary  float-left"
                                                        style="width: 6rem; height: 40px; margin: 2px; white-space: nowrap !important; overflow: hidden;  text-overflow: ellipsis;">
                                                        <span title="{{ $employee->name }}" style="line-height: 40px;">
                                                            {{ $employee->name }}
                                                        </span>
                                                    </div>
                                                </a>
                                            @endif
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                            <td class="align-middle text-center"></td>
                            <td class="align-middle text-center"></td>
                            <td class="align-middle text-center"></td>
                            <!--Active-->
                            <td class="align-middle">
                                <a href="{{ route('project.get.detail', ['id' => $project->id]) }}"
                                    class="btn btn-primary m-1">Xem
                                    chi tiết</a>
                                <a href="{{ route('project.get.viewUpdate', ['id' => $project->id]) }}"
                                    class="btn btn-primary m-1">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <a href="{{ route('project.destroy', ['id' => $project->id]) }}"
                                    onclick="return confirm('Bạn có muốn xóa không ?')" class="btn btn-danger m-1">
                                    <i class="fa fa-trash "></i>
                                </a>
                            </td>
                            <!--Active-->
                        </tr>
                    @endif
                @endforeach
            @endif
        </tbody>
        <!--Project status  1-->
        <!--Project status # 1-->
        <tbody id="finished-Project-view">
            @if (count($listProject) > 0)
                @php
                    $count = 0;
                @endphp
                @foreach ($listProject as $project)
                    @if ($project->status != '1')
                        <tr class="search">
                            <td class="align-middle text-center">{{ ++$count }}</td>
                            <td class="align-middle text-center">{{ $project->name }}</td>
                            <td class="align-middle text-center">
                                @if ($project->users)
                                    @foreach ($project->users->unique('name') as $employee)
                                        <!--Get month Current and year Current-->
                                        @php
                                            $month = $carbon::parse($employee->day_addEmp)->format('m');
                                            $year = $carbon::parse($employee->day_addEmp)->format('Y');
                                            $monthCurrent = $carbon::now()->month;
                                            $yearCurrent = $carbon::now()->year;
                                        @endphp
                                        <!--Get month Current and year Current-->
                                        <a style=" cursor: default;" href="javascript:void(0)">
                                            <div class="badge bg-secondary  float-left"
                                                style="width: 6rem; height: 40px; margin: 2px; white-space: nowrap !important; overflow: hidden;  text-overflow: ellipsis;">
                                                <span title="{{ $employee->name }}" style="line-height: 40px;">
                                                    {{ $employee->name }}
                                                </span>
                                            </div>
                                        </a>
                                    @endforeach
                                @endif
                            </td>
                            <td class="align-middle text-center"></td>
                            <td class="align-middle text-center"></td>
                            <td class="align-middle text-center"></td>
                            <!--Active-->
                            <td class="align-middle">
                                <a href="{{ route('project.get.detail', ['id' => $project->id]) }}"
                                    class="btn btn-primary m-1">Xem
                                    chi tiết</a>
                                <a href="{{ route('project.get.viewUpdate', ['id' => $project->id]) }}"
                                    class="btn btn-primary m-1">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <a href="{{ route('project.destroy', ['id' => $project->id]) }}"
                                    onclick="return confirm('Bạn có muốn xóa không ?')" class="btn btn-danger m-1">
                                    <i class="fa fa-trash "></i>
                                </a>
                            </td>
                            <!--Active-->
                        </tr>
                    @endif
                @endforeach
            @endif
        </tbody>
        <!--Project status # 1-->
    </table>
</div>
@endsection
@push('scripts')
<script src="{{ asset("$base_url/js/search.js") }}"></script>
<script>
    $('#active-Project-view').show();
    $('#finished-Project-view').hide();
    $('#active-Project').attr('class', 'active btn btn-primary');
    $('#active-Project').click(function(e) {
        $('#active-Project').attr('class', 'active btn btn-primary');
        $('#finished-Project').attr('class', ' btn btn-secondary');
        $('#active-Project-view').show();
        $('#finished-Project-view').hide();
    });
    $('#finished-Project').click(function(e) {
        $('#finished-Project-view').show();
        $('#active-Project-view').hide();
        $('#active-Project').attr('class', 'btn btn-secondary');
        $('#finished-Project').attr('class', 'active btn btn-primary');
    })
</script>
@endpush
