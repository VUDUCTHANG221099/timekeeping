{{-- @dd($employee->status); --}}
@extends('layouts.master')
@section('title')
    @if (Request::routeIs('employee.get.viewCreate'))
        Thêm nhân viên
    @endif
    @if (Request::routeIs('employee.get.viewUpdate'))
        Cập nhật nhân viên
    @endif
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('employee.get.all') }}">Nhân viên</a></li>
    @if (Request::routeIs('employee.get.viewCreate'))
        <li class="breadcrumb-item active">Thêm nhân viên</li>
    @endif
    @if (Request::routeIs('employee.get.viewUpdate'))
        <li class="breadcrumb-item active"> Cập nhật nhân viên</li>
    @endif
@endsection
@section('content')
    <div class="container">
        @if (Request::routeIs('employee.get.viewCreate'))
            <p class="h3">#{{ $emp_code }}</p>
        @endif
        <form
            action="
        @if (Request::routeIs('employee.get.viewCreate')) {{ route('employee.post.create') }} @endif
    @if (Request::routeIs('employee.get.viewUpdate')) {{ route('employee.put.update') }} @endif
        "
            method="post">
            @csrf
            @if (Request::routeIs('employee.get.viewCreate'))
                @method('POST')
            @endif
            @if (Request::routeIs('employee.get.viewUpdate'))
                @method('PUT')
            @endif
            <div class="form-row">
                <div class="col">
                    <label for="fullname">Họ và tên</label>
                    <input type="hidden" value="{{ $employee->id ?? false }}" name="id" id="id">
                    <input type="text" class="form-control" placeholder="VD: Nguyễn Văn A" name="fullname" id="fullname"
                        autocomplete="fullname" autofocus value="{{ old('fullname') ?? ($employee->name ?? false) }}">
                    <span class="text-danger danger_hide">
                        @error('fullname')
                            {{ $message }}
                        @enderror
                    </span>
                </div>
                <div class="col">
                    <label for="email">Email</label>
                    <input type="text" autocomplete="off" class="form-control" placeholder="VD: abc@vaixgroup.com"
                        name="email" id="email" value="{{ old('email') ?? ($employee->email ?? false) }}"
                        {{ Request::routeIs('employee.get.viewUpdate') ? 'disabled' : false }}>
                    <span class="text-danger danger_hide"> @error('email')
                            {{ $message }}
                        @enderror
                    </span>
                </div>
            </div>
            <div class="form-row">
                <div class="col">
                    @if (Request::routeIs('employee.get.viewCreate'))
                        <label for="password">Mật khẩu</label>
                        <input type="password" class="form-control" autocomplete="new-password" placeholder="Mật khẩu"
                            name="password" id="password">
                        <span class="text-danger danger_hide"> @error('password')
                                {{ $message }}
                            @enderror
                            {{ session()->get('password') ?? false }}
                        </span>
                    @endif
                </div>
                <div class="col">
                    @if (Request::routeIs('employee.get.viewCreate'))
                        <label for="password_confirmation">Xác nhận Mật khẩu</label>
                        <input type="password" class="form-control" placeholder="Nhập lại Mật khẩu"
                            name="password_confirmation" id="password_confirmation" autocomplete="password_confirmation">
                        <span class="text-danger danger_hide">
                            @error('password_confirmation')
                                {{ $message }}
                            @enderror
                        </span>
                    @endif
                    @if (Request::routeIs('employee.get.viewUpdate'))
                        <h4><a href="{{ route('employee.get.changdPassword', ['id' => $employee->id]) }}">
                                Cập nhật mật khẩu
                            </a>
                        </h4>
                    @endif
                </div>
            </div>
            {{-- <div class="form-row d-none">
            <div class="col">
                <label for="birthday">Ngày sinh</label>
                <input type="text" placeholder="Select Date.." class="form-control" name="birthday" id="flatpickr"
                    value="{{ old('birthday') ?? ($employee->birthday ?? false) }}" />
                <span class="text-danger danger_hide"> @error('birthday')
                    {{ $message }}
                    @enderror
                </span>
            </div>
            <div class="col">
                <label>Giới tính</label>
                <div class="col mb-2">
                    Nam: <input type="radio" name="gender" value="1" @if (Request::routeIs('employee.get.viewCreate'))
                        {{ old('gender')=='1' ? 'checked' : false }} @endif @if (Request::routeIs('employee.get.viewUpdate')) {{ $employee->gender == '1' ? 'checked' : false }}
                    @endif>
                    Nữ: <input type="radio" name="gender" value="0" @if (Request::routeIs('employee.get.viewCreate')) {{
                        old('gender')=='0' ? 'checked' : false }} @endif @if (Request::routeIs('employee.get.viewUpdate')) {{ $employee->gender == '0' ? 'checked' : false }}
                    @endif>
                </div>
                <span class="text-danger danger_hide"> @error('gender')
                    {{ $message }}
                    @enderror
                </span>
            </div>
            <div class="col">
                <label for="phone">Số điện thoại</label>
                <input type="number" class="form-control" placeholder="Số điện thoại" name="phone" id="phone"
                    value="{{ old('phone') ?? ($employee->phone ?? false) }}" step="any">
                <span class="text-danger danger_hide">
                    @error('phone')
                    {{ $message }}
                    @enderror
                </span>
            </div>
        </div>
        <div class="form-row d-none">
            <div class="col">
                <label for="address">Địa chỉ</label>
                <input type="text" class="form-control"
                    placeholder="VD: JMH Building, No. 16 Branch 45 Dong Me, Me Tri, Nam Tu Liem, Ha Noi" name="address"
                    id="address" value="{{ old('address') ?? ($employee->address ?? false) }}">
                <span class="text-danger danger_hide"> @error('address')
                    {{ $message }}
                    @enderror
                </span>
            </div>
        </div> --}}
            <div class="form-row">
                <div class="col">
                    <label for="roles">Vai trò</label>
                    <select class="form-control" name="roles" id="roles">
                        <option hidden value="">Select</option>
                        @if (isset($roles) and count($roles) > 0)
                            @foreach ($roles as $item)
                                <option value="{{ $item->id }}"
                                    @if (Request::routeIs('employee.get.viewCreate')) {{ old('roles') == $item->id ? 'selected' : false }} @endif
                                    @if (Request::routeIs('employee.get.viewUpdate')) {{ $employee->roles[0]->id == $item->id ? 'selected' : false }} @endif>
                                    {{ $item->display_name }}</option>
                            @endforeach
                        @endif
                    </select>
                    <span class="text-danger danger_hide">
                        @error('roles')
                            {{ $message }}
                        @enderror
                    </span>
                </div>
                @if (Request::routeIs('employee.get.viewUpdate'))
                    <div class="col">
                        <label for="status">Trạng thái</label>
                        <select class="form-control" name="status" id="status">
                            <option hidden value="">Select</option>
                            <option value="1" {{ $employee->status == '1' ? 'selected' : '' }} class="text-primary">
                                Hoạt động</option>
                            <option value="0" {{ $employee->status == '0' ? 'selected' : '' }} class="text-danger">
                                Đã nghỉ việc</option>
                        </select>
                    </div>
                @endif
                {{-- <div class="col d-none">
                <label for="positions">Chức vụ</label>
                <select class="form-control" name="positions" id="positions">
                    <option hidden value="">Select</option>
                    @if (isset($positions) and count($positions) > 0)
                    @foreach ($positions as $item)
                    <option value="{{ $item->id }}" @if (Request::routeIs('employee.get.viewCreate')) {{
                        old('positions')==$item->id ? 'selected' : false }} @endif
                        @if (Request::routeIs('employee.get.viewUpdate')) {{ $employee->positions[0]->id == $item->id ?
                        'selected' : false }} @endif>
                        {{ $item->display_name }}</option>
                    @endforeach
                    @endif
                </select>
                <span class="text-danger danger_hide">
                    @error('positions')
                    {{ $message }}
                    @enderror
                </span>
            </div>
            <div class="col d-none">
                <label for="salary">Lương</label>
                <div style="display:flex">
                    <input type="number" id="salary" name="salary" class="form-control w-100"
                        placeholder="VD: 2.000.000 VNĐ" value="{{ old('salary') ?? ($employee->salary ?? false) }}"
                        step="any">
                    <span class="line-base" style="font-size:25px">
                        VNĐ
                    </span>
                </div>
                <span class="text-danger danger_hide">
                    @error('salary')
                    {{ $message }}
                    @enderror
                </span>
                <span id="checkNumber"></span>
            </div> --}}
            </div>
            <div class="form-row">
                <div class="col mt-3">
                    @if (Request::routeIs('employee.get.viewCreate'))
                        <button type="submit" class="btn btn-primary">Thêm</button>
                        <button type="reset" class="btn btn-danger">Làm mới</button>
                    @endif
                    @if (Request::routeIs('employee.get.viewUpdate'))
                        <button type="submit" class="btn btn-primary"
                            onclick="return confirm('Bạn có muốn cập nhật không ?')">Cập nhật</button>
                    @endif
                </div>
            </div>
        </form>
    </div>
@endsection
@push('scripts')
    {{--
<script src={{ asset("$base_url/js/flatpickr.js") }}></script> --}}
    {{-- /*
$(document).ready(function() {
var regex = /^[0-9]+$/;
$('#salary').on("keyup", function(e) {
var input = $(this).val();
if ($.isNumeric(input)) {
$('#checkNumber').html(null);
// $('#checkNumber').attr('display','none');
}
if (!$.isNumeric(input) && input.length > 0) {
$('#checkNumber').attr('class', 'text-danger', "h3");
// $('#checkNumber').attr('class', 'h3');
$('#checkNumber').html("Vui lòng nhập số!");
}
if (input === '') {
$('#checkNumber').html(null);
}
})
})*/ --}}
@endpush
