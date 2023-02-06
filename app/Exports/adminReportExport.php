<?php
namespace App\Exports;

use App\Http\Controllers\API\APIadminReportController;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class adminReportExport implements FromView, WithTitle
{
    protected $year;
    protected $month;
    protected $daysInMonth;
    protected $adminReport;
    public function __construct($year, $month, $daysInMonth)
    {
        $this->year = $year;
        $this->month = $month;
        $this->daysInMonth = $daysInMonth;
        $this->adminReport = new APIadminReportController();
    }
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function view(): View
    {
        $yearAndMonth = "$this->year-$this->month";
        $list = $this->adminReport->ProjectAndEmployeeToMonthExport($yearAndMonth);
        $data = [
            'data' => $list,
            'daysInMonth' => $this->daysInMonth,
        ];
        return view('report.exportAdminReport', $data);
    }
    /**
     * @return string
     */
    public function title(): string
    {
        return "Bảng chấm công tháng $this->month-$this->year";
    }
}
