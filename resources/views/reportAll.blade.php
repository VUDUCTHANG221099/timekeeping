<div class="container-full">
    <div class="d-flex justify-content-center mb-3">
        <input id="month_flatpickr" name="month" value="today" placeholder="Select Date.." class="form-control"
            type="text" style="display: inline !important;width: 18%" />
        <a class="input-button"data-toggle="collapse" href="#month_flatpickr" role="button" aria-expanded="false"
            aria-controls="month_flatpickr" style="margin: 9px 5px 0px -20px">
            <i class="fas fa-calendar-alt"></i>
        </a>
    </div>
    <div id="reportAllofEmployee" style="overflow-x:auto;">
    </div>
    <div class="text-center mt-3" id="btnExportAll">
    </div>
</div>
<!--Link Employee detail-->
<span id="detailEmployee" class="d-none">{{ route('employee.get.detail') }}</span>
<!--Link Employee detail-->
<!--Link Export Excel-->
<span id="exportExcelAll" class="d-none">{{ route('exportAll') }}</span>
<!--Link Export Excel-->
