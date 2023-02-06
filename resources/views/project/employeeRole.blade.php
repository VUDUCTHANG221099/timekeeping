@extends('layouts.master')
@section('title')
    Nhân viên và dự án
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('project.get.all') }}">Dự án</a></li>
    <li class="breadcrumb-item"><a href="{{ route('project.get.detail', ['id' => $id]) }}">{{ $name }}</a></li>
    <li class="breadcrumb-item active"> @yield('title')
    </li>
@endsection
@section('content')
    <div class="container">
        <div class="container">
            <form class="form-group d-flex">
                <input class="form-control" type="text" id="keywords" placeholder="Search" autofocus>
                <button class="btn btn-outline-success bg-info" type="submit">Search</button>
            </form>
        </div>
        <form method="POST" action="{{ route('projectAddEmployee') }}">
            @method('PUT')
            <table class="table" id="tableEmp">
                @csrf
                <thead class="thead-dark sticky">
                    <tr>
                        <th scope="col">Nhân viên</th>
                        @foreach ($listRole as $item)
                            <th scope="col">{{ ucfirst($item->name) }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($listEmp as $employee)
                        <tr>
                            <td>{{ $employee->name }}
                            </td>
                            @foreach ($listRole as $role)
                                <td>
                                    <input type="checkbox" value="{{ $role->id }}" name="roles[{{ $employee->id }}][]"
                                        id="roles"
                                        {{ count($userRoles) && !empty($userRoles[$employee->id]) && in_array($role->id, $userRoles[$employee->id])
                                            ? 'checked'
                                            : '' }}>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <input type="hidden" name="idProject" value="{{ $id }}">
            <button class="btn btn-primary" type="submit" id="addEmployee">Phân công</button>
        </form>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset("$base_url/js/search.js") }}"></script>
@endpush
