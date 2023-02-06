@extends('layouts.master')
@section('title')
    @if (Request::routeIs('position.get.viewCreate'))
        Thêm vai trò
    @endif
    @if (Request::routeIs('position.get.viewUpdate'))
        Cập nhật vai trò
    @endif
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('position.get.all') }}">Vai trò</a></li>
    @if (Request::routeIs('position.get.viewCreate'))
        <li class="breadcrumb-item active"> @yield('title') </li>
    @endif
    @if (Request::routeIs('position.get.viewUpdate'))
        <li class="breadcrumb-item active"> @yield('title') </li>
    @endif
@endsection
@section('content')
    <h3 class="ml-3"></h3>
    <div class="container w-50">
        <form
            action="
        @if (Request::routeIs('position.get.viewCreate')) {{ route('position.post.create') }} @endif
        @if (Request::routeIs('position.get.viewUpdate')) {{ route('position.put.update') }} @endif
        "method="post">
            @csrf
            @if (Request::routeIs('position.get.viewCreate'))
                @method('POST')
            @endif
            @if (Request::routeIs('position.get.viewUpdate'))
                @method('PUT')
            @endif
            <div class="form-row ">
                <div class="col">
                    <label for="namePosition">Tên viết tắt</label>
                    <input type="hidden" name="id" value="{{ $position->id ?? false }}">
                    <input type="namePosition" class="form-control" placeholder="VD: Dev" name="namePosition"
                        id="namePosition" value="{{ old('namePosition') ?? ($position->name ?? false) }}"
                        {{ Request::routeIs('position.get.viewUpdate') ? 'disabled' : false }}>
                    <span class="text-danger danger_hide">
                        @error('namePosition')
                            {{ $message }}
                        @enderror
                    </span>
                </div>
            </div>
            <div class="form-row">
                <div class="col">
                    <label for="display_name">Tên hiển thị</label>
                    <input type="text" class="form-control" placeholder="VD: Developer" name="display_name"
                        id="display_name" value="{{ old('display_name') ?? ($position->display_name ?? false) }}">
                    <span class="text-danger danger_hide">
                        @error('display_name')
                            {{ $message }}
                        @enderror
                    </span>
                </div>
            </div>
            <div class="form-row">
                <div class="col mt-3">
                    @if (Request::routeIs('position.get.viewCreate'))
                        <button type="submit" class="btn btn-primary">Thêm</button>
                        <button type="reset" class="btn btn-danger">Làm mới</button>
                    @endif
                    @if (Request::routeIs('position.get.viewUpdate'))
                        <button type="submit" class="btn btn-primary"
                            onclick="return confirm('Bạn có muốn cập nhật không ?')">Cập nhật</button>
                    @endif
                </div>
            </div>
        </form>
    </div>
@endsection
