@extends('layouts.master')
@section('title', 'Dashboard')
@push('css')
    <link rel="stylesheet" href="https://unpkg.com/flatpickr@4.6.9/dist/plugins/monthSelect/style.css">
@endpush
@section('content')
    <div class="ml-2">
        <p class="text-success flashMessage">{{ session()->get('success') ?? false }}</p>
        <a class="ml-3 text-dark" href="{{ route('project.get.all') }}">{{ session()->get('successProject') ?? false }}</a>
        <a class="ml-3  text-dark" href="{{ route('position.get.all') }}">{{ session()->get('successPosition') ?? false }}</a>
        <a class="ml-3  text-dark" href="{{ route('employee.get.all') }}">{{ session()->get('successEmployee') ?? false }}</a>
    </div>
    @include('reportAll')
    <span id="reportAll" class="d-none">{{ route('apiReportAllEmployeeMonth') }}</span>
    <span id="exportOneExcel" class="d-none" data-exportOneExcel="{{ route('exportOneReport') }}"></span>
@endsection
@push('scripts')
    <script src="https://unpkg.com/flatpickr@4.6.9/dist/plugins/monthSelect/index.js"></script>
    <script src="{{ asset("$base_url/js/reportAll.js") }}"></script>
@endpush
