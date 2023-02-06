<?php
namespace App\Exports;

use App\Models\employeeProject;
use Illuminate\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class ReportEmployeeInAdminExport implements FromView, WithTitle
{
    protected $year;
    protected $month;
    protected $idUser;
    protected $daysInMonth;
    protected $employeeProject;
    public function __construct(
        $year,
        $month,
        $idUser,
        $daysInMonth
    )
    {
        $this->year = $year;
        $this->month = $month;
        $this->idUser = $idUser;
        $this->daysInMonth = $daysInMonth;
        $this->employeeProject = new employeeProject();
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
        $list = $this->employeeProject
            ->join('projects', 'projects.id', 'employee_project.project_id')
            ->where(['employee_project.user_id' => $this->idUser])
            ->whereMonth('employee_project.day_work', $this->month)
            ->whereYear('employee_project.day_work', $this->year)
            ->select('projects.name', 'working_hours', 'day_work')
            ->get();
        $data = [
            'list' => $list,
            'year' => $this->year,
            'month' => $this->month,
            'daysInMonth' => $this->daysInMonth
        ];
        return view('report.export_table_detal', $data);
    }
}
