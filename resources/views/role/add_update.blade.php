@extends('layouts.master')
@section('title')
    @if (Request::routeIs('role.get.viewCreate'))
        Thêm phân quyền
    @endif
    @if (Request::routeIs('role.get.viewUpdate'))
        Cập nhật phân quyền
    @endif
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('role.get.all') }}">Phân quyền</a></li>
    @if (Request::routeIs('role.get.viewCreate'))
        <li class="breadcrumb-item active"> @yield('title') </li>
    @endif
    @if (Request::routeIs('role.get.viewUpdate'))
        <li class="breadcrumb-item active"> @yield('title') </li>
    @endif
@endsection
@section('content')
    <h3 class="ml-3"></h3>
    <div class="container w-50">
        <form
            action="
        @if (Request::routeIs('role.get.viewCreate')) {{ route('role.post.create') }} @endif
        @if (Request::routeIs('role.get.viewUpdate')) {{ route('role.put.update') }} @endif
        "
            method="post">
            @csrf
            @if (Request::routeIs('role.get.viewCreate'))
                @method('POST')
            @endif
            @if (Request::routeIs('role.get.viewUpdate'))
                @method('PUT')
            @endif
            <div class="form-row ">
                <div class="col">
                    <label for="nameRole">Tên viết tắt</label>
                    <input type="hidden" name="id" value="{{ $role->id ?? false }}">
                    <input type="text" class="form-control" placeholder="VD: admin" name="nameRole" id="nameRole"
                        value="{{ old('nameRole') ?? ($role->name ?? false) }}"
                        {{ Request::routeIs('role.get.viewUpdate') ? 'disabled' : false }}>
                    <span class="text-danger danger_hide">
                        @error('nameRole')
                            {{ $message }}
                        @enderror
                    </span>
                </div>
            </div>
            <div class="form-row">
                <div class="col">
                    <label for="display_name">Tên hiển thị</label>
                    <input type="text" class="form-control" placeholder="VD: Quản trị viên" name="display_name"
                        id="display_name" value="{{ old('display_name') ?? ($role->display_name ?? false) }}">
                    <span class="text-danger danger_hide">
                        @error('display_name')
                            {{ $message }}
                        @enderror
                    </span>
                </div>
            </div>
            <div class="form-row">
                <div class="col mt-3">
                    @if (Request::routeIs('role.get.viewCreate'))
                        <button type="submit" class="btn btn-primary">Thêm</button>
                        <button type="reset" class="btn btn-danger">Làm mới</button>
                    @endif
                    @if (Request::routeIs('role.get.viewUpdate'))
                        <button type="submit" class="btn btn-primary"
                        onclick="return confirm('Bạn có muốn cập nhật không ?')"
                        >Cập nhật</button>
                    @endif
                </div>
            </div>
        </form>
    </div>
@endsection
