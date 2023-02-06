@extends('layouts.master')
@section('title')
    @if (Request::routeIs('project.get.viewCreate'))
        Thêm dự án
    @endif
    @if (Request::routeIs('project.get.viewUpdate'))
        Cập nhật dự án
    @endif
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('project.get.all') }}">Dự án</a></li>
    @if (Request::routeIs('project.get.viewCreate'))
        <li class="breadcrumb-item active"> @yield('title')
        </li>
    @endif
    @if (Request::routeIs('project.get.viewUpdate'))
        <li class="breadcrumb-item active"> @yield('title')
        </li>
    @endif
@endsection
@section('content')
    <div class="container">
        <form
            action="
        @if (Request::routeIs('project.get.viewCreate')) {{ route('project.post.create') }} @endif
    @if (Request::routeIs('project.get.viewUpdate')) {{ route('project.put.update') }} @endif
        "
            method="post">
            @csrf
            @if (Request::routeIs('project.get.viewCreate'))
                @method('POST')
            @endif
            @if (Request::routeIs('project.get.viewUpdate'))
                @method('PUT')
            @endif
            <div class="form-row">
                <div class="col">
                    <label for="name">Tên dự án</label>
                    <input type="hidden" value="{{ $project->id ?? false }}" name="id">
                    <input type="text" class="form-control" placeholder="VD: Vaix Daily Report System" name="name"
                        id="name" autocomplete="name" autofocus value="{{ old('name') ?? ($project->name ?? false) }}">
                    <span class="text-danger danger_hide">
                        @error('name')
                            {{ $message }}
                        @enderror
                    </span>
                </div>
            </div>
            <div class="form-row d-none">
                <div class="col">
                    <label for="start_project">Ngày bắt đầu</label>
                    <input type="text" placeholder="Select Date.." class="form-control" name="start_project"
                        id="flatpickr_start" value="{{ old('start_project') ?? ($project->start_project ?? false) }}">
                    <span class="text-danger danger_hide"> @error('start_project')
                            {{ $message }}
                        @enderror
                    </span>
                </div>
                <div class="col">
                    <label for="end_project">Ngày kết thúc</label>
                    <input type="text" placeholder="Select Date.." class="form-control" name="end_project"
                        id="flatpickr_end" value="{{ old('end_project') ?? ($project->end_project ?? false) }}">
                    <span class="text-danger danger_hide">
                    </span>
                </div>
            </div>
            <div class="form-row">
                <div class="col">
                    <label for="status">Trạng thái</label>
                    @php
                        $status_All = [
                            [
                                'name' => 'Open',
                                'value' => '1',
                                'status' => 'primary',
                            ],
                            [
                                'name' => 'Pending',
                                'value' => '0',
                                'status' => 'warning',
                            ],
                            [
                                'name' => 'Closed',
                                'value' => '2',
                                'status' => 'danger',
                            ],
                        ];
                    @endphp
                    <select class="form-control" name="status" id="status">
                        <option hidden value="">Select</option>
                        @foreach ($status_All as $key => $value)
                            <option value="{{ $status_All[$key]['value'] }}"
                                @if (Request::routeIs('project.get.viewCreate')) {{ old('status') == $status_All[$key]['value'] ? 'selected' : false }} @endif
                                @if (Request::routeIs('project.get.viewUpdate')) {{ $project->status == $status_All[$key]['value'] ? 'selected' : false }} @endif
                                class="text-{{ $status_All[$key]['status'] }}">{{ $status_All[$key]['name'] }}
                            </option>
                        @endforeach
                    </select>
                    <span class="text-danger danger_hide"> @error('status')
                            {{ $message }}
                        @enderror
                    </span>
                </div>
                <div class="col d-none">
                    <label for="menber">Thành viên</label>
                    <input type="number" value="{{ old('menber') ?? ($project->menber ?? false) }}" name="menber"
                        id="menber"class="form-control" placeholder="VD: 25">
                    <span class="text-danger danger_hide"> @error('menber')
                            {{ $message }}
                        @enderror
                    </span>
                </div>
                <div class="co d-none">
                    <label for="cost">Giá tiền</label>
                    <div style="display: flex">
                        <input type="number" name="cost" value="{{ old('cost') ?? ($project->cost ?? false) }}"
                            id="cost"class="form-control" placeholder="VD: 25.000.000 VNĐ">
                        <span class="line-base" style="font-size:25px">
                            VNĐ
                        </span>
                    </div>
                    <span class="text-danger danger_hide"> @error('cost')
                            {{ $message }}
                        @enderror
                    </span>
                </div>
            </div>
            <div class="form-row">
                <div class="col">
                    <label for="description">Mô tả</label>
                    <textarea class="form-control" name="description" id="description" placeholder="Mô tả đôi chút về dự án!">{{ Request::routeIs('project.get.viewCreate') ? old('description') : false }}{{ Request::routeIs('project.get.viewUpdate') ? $project->description : false }}</textarea>
                </div>
            </div>
            <div class="form-row">
                <div class="col mt-3">
                    @if (Request::routeIs('project.get.viewCreate'))
                        <button type="submit" class="btn btn-primary">Thêm</button>
                        <button type="reset" class="btn btn-danger">Làm mới</button>
                    @endif
                    @if (Request::routeIs('project.get.viewUpdate'))
                        <button type="submit" class="btn btn-primary"
                            onclick="return confirm('Bạn có muốn cập nhật không ?')">Cập nhật</button>
                    @endif
                </div>
            </div>
        </form>
    </div>
@endsection
@push('scripts')
    {{-- <script src={{ asset("$base_url/js/flatpickr.js") }}></script> --}}
@endpush
