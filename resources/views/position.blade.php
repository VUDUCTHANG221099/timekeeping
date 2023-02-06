@extends('layouts.master')
@section('title', 'Vai trò')
@section('breadcrumb')
    <li class="breadcrumb-item active">@yield('title')</li>
@endsection
@section('content')
    <div class="container-full row">
        <div class="col align-middle m-3">
            <span class="text-success flashMessage">{{session()->get('success') ?? false}}</span>
        </div>
        <div class="col">
            <a href="{{ route('position.get.viewCreate') }}" class="btn btn-primary float-right mt-2 mb-4">
                <i class="fa fa-plus" aria-hidden="true"></i>
            </a>
        </div>
    </div>
    <table class="table">
        <thead class="thead-dark">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Tên viết tắt</th>
                <th scope="col">Tên hiển thị</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @if (isset($position) and count($position) > 0)
                @foreach ($position as $key => $value)
                    <tr>
                        <th class="align-middle" scope="row">{{ $count++ }}</th>
                        <td class="align-middle">{{ $value->name }}</td>
                        <td class="align-middle">{{ $value->display_name }}</td>
                        <td class="align-middle">
                            <a href="{{ route('position.get.viewUpdate', ['id'=>$value->id]) }}"
                            >
                                <i class="fa-solid fa-pen-to-square text-primary"></i>
                            </a>
                            <a href="{{ route('position.destroy', ['id'=>$value->id]) }}"
                                onclick="return confirm('Bạn có muốn xóa không ?')"
                                >
                                <i class="fa fa-trash text-danger"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
@endsection
