<?php
namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithTitle;

class ReportExport implements FromView, WithTitle
{
    protected $year;
    protected $month;
    protected $daysInMonth;
    protected $userId;
    public function __construct($year, $month, $daysInMonth, $userId)
    {
        $this->year = $year;
        $this->month = $month;
        $this->daysInMonth = $daysInMonth;
        $this->userId = $userId;
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
        $listProject = DB::table('employee_project')
            ->whereMonth('day_work', '=', $this->month)
            ->whereYear('day_work', '=', $this->year)
            ->where('user_id', $this->userId)
            ->join('projects', 'projects.id', 'employee_project.project_id')
            ->distinct()
            ->select('projects.name as nameProject', 'projects.id as idProject')->get()->toArray();
        $listDays = DB::table('employee_project')
            ->whereMonth('day_work', '=', $this->month)
            ->whereYear('day_work', '=', $this->year)
            ->where('user_id', $this->userId)->orderBy("employee_project.day_work")
            ->join('projects', 'projects.id', 'employee_project.project_id')
            ->select(
                'employee_project.project_id as idProject',
                'employee_project.day_work',
                'employee_project.working_hours'
            )->get()->toArray();
        if (count($listDays) > 0 and count($listProject) > 0) {
            $daysAndHours = [];
            for ($i = 0; $i < count($listProject); $i++) {
                for ($j = 0; $j < count($listDays); $j++) {
                    if ($listProject[$i]->idProject == $listDays[$j]->idProject) {
                        $daysAndHours[] = ([
                            'day_work' => (int) Carbon::parse($listDays[$j]->day_work)->translatedFormat('d'),
                            'hours' => $listDays[$j]->working_hours
                        ]);
                        $list[$i] = [
                            'nameProject' => $listProject[$i]->nameProject,
                            'idProject' => $listProject[$i]->idProject,
                            'days' => $daysAndHours
                        ];
                    }
                }
                $daysAndHours = [];
            }
        }
        $data = [
            'list' => $list,
            'year' => $this->year,
            'month' => $this->month,
            'daysInMonth' => $this->daysInMonth,
        ];
        // dd($data['list']);
        return view('report.table', $data);
    }
}
