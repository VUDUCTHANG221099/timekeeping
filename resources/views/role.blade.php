@extends('layouts.master')
@section('title', 'Phân quyền')
@section('breadcrumb')
    <li class="breadcrumb-item active">@yield('title')</li>
@endsection
@section('content')
    <div class="container-full row">
        <div class="col align-middle m-3">
            <span class="text-success flashMessage">{{ session()->get('success') ?? false }}</span>
        </div>
        <div class="col">
            <a href="{{ route('role.get.viewCreate') }}" class="btn btn-primary float-right mt-2 mb-4">
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
            @php
                $countRole = 0;
            @endphp
            {{-- @dd($user_role[$countRole]->role_id); --}}
            @if (isset($role) and count($role) > 0)
                @foreach ($role as $key => $value)
                    <tr>
                        <th class="align-middle" scope="row">{{ $count++ }}</th>
                        <td class="align-middle">{{ $value->name }}</td>
                        <td class="align-middle">{{ $value->display_name }}</td>
                        <td class="align-middle">
                            <a href="{{ route('role.get.viewUpdate', ['id' => $value->id]) }}"
                               >
                                <i class="fa-solid fa-pen-to-square text-primary"></i>
                            </a>
                            @if (!in_array($value->id, $user_role))
                                <a href="{{ route('role.destroy', ['id' => $value->id]) }}"
                                    onclick="return confirm('Bạn có muốn xóa không ?')">
                                    <i class="fa fa-trash text-danger"></i>
                                </a>
                            @endif
                        </td>
                    </tr>
                    @php
                        $countRole += 1;
                    @endphp
                @endforeach
            @endif
        </tbody>
    </table>
@endsection
