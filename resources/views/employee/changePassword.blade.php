@extends('layouts.master')
@section('breadcrumb')
    @can('policy', Auth::user())
        @section('title', 'Cập nhật mật khẩu')
        <li class="breadcrumb-item"><a href="{{ route('employee.get.all') }}">Nhân viên</a></li>
        <li class="breadcrumb-item active"> Cập nhật mật khẩu</li>
    @endcan
    @can('policyEmployee', Auth::user())
        @section('breadcrumb')
        @section('title', 'Đổi mật khẩu')

        <li class="breadcrumb-item active"> Đổi mật khẩu</li>
    @endcan
@endsection
@section('content')
    <h3 class="ml-3">{{ $employee->name }}</h3>
    <div class="container w-50">

        <form action="{{ route('employee.put.changdPassword') }}" method="post">
            @csrf
            @method(' put')

            <div class="form-row ">
                <div class="col">
                    <label for="password">Mật khẩu</label>
                    <input type="hidden" name="id" value="{{ $employee->id }}">

                    <div class="d-flex justify-content-center">
                        <input type="password" class="form-control" placeholder="Mật khẩu" name="password" id="password"
                            value="{{ old('password') ?? false }}">
                        <span id="icon-eye-pass" style="margin: 7px 5px 0px -23px">
                            <i id="eye-pass" class="fa-sharp fa-solid fa-eye-slash"></i>
                        </span>
                    </div>
                    <span class="text-danger danger_hide">
                        @error('password')
                            {{ $message }}
                        @enderror
                    </span>
                </div>



            </div>
            <div class="form-row">
                <div class="col">
                    <label for="password_confirmation">Xác nhận Mật khẩu</label>

                    <div class="d-flex justify-content-center">
                    <input type="password" class="form-control" placeholder="Xác nhận Mật khẩu" name="password_confirmation"
                        id="password_confirmation" autocomplete="password_confirmation">
                        <span id="icon-eye-pass-confirm" style="margin: 7px 5px 0px -23px">
                            <i id="eye-pass-confirm" class="fa-sharp fa-solid fa-eye-slash"></i>
                        </span>
                    </div>
                    <span class="text-danger danger_hide">
                        @error('password_confirmation')
                            {{ $message }}
                        @enderror
                    </span>
                </div>
            </div>
            <div class="form-row">
                <div class="col mt-3">



                    <button type="submit" class="btn btn-primary">Submit</button>

                </div>

            </div>
        </form>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset("$base_url/js/eye.js") }}"></script>
@endpush
