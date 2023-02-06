<?php
namespace App\Exports;

use App\Http\Controllers\API\reportAllController;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class ReportAllExport extends reportAllController implements FromView, WithTitle
{
    protected $year;
    protected $month;
    protected $daysInMonth;
    protected $reportAll;
    public function __construct($year, $month, $daysInMonth)
    {
        $this->year = $year;
        $this->month = $month;
        $this->daysInMonth = $daysInMonth;
        $this->reportAll = new reportAllController();
    }
    /**
     * Summary of WithTitle
     * @return string
     */
    public function title(): string
    {
        return "Bảng chấm công tháng $this->month-$this->year";
    }
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function view(): View
    {
        $yearAndMonth = $this->year . '-' . $this->month;
        //TODO: encode
        $jsonEncode = (array) json_encode($this->reportAll->index($yearAndMonth));
        //TODO: decode
        $jsonDecode = json_decode($jsonEncode[0]);
        $list = (array) $jsonDecode->original->list;
        $data = [
            'list' => $list,
            'year' => $this->year,
            'month' => $this->month,
            'daysInMonth' => $this->daysInMonth,
        ];
        return view('report.export-all', $data);
    }
}
