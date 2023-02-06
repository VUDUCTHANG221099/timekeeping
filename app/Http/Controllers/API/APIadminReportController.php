<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\employeeProject;
use App\Models\Plan;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class APIadminReportController extends Controller
{
    protected $plan;
    protected $project;
    protected $user;
    protected $employeeProject;
    public function __construct()
    {
        $this->plan = new Plan();
        $this->project = new Project();
        $this->user = new User();
        $this->employeeProject = new employeeProject();
    }
    /**
     * Summary of scheduleAndReality
     * @param mixed $yearAndMonth
     * @return void Get list project and schedule
     */
    public function scheduleAndReality($yearAndMonth)
    {
        try {
            //TODO format Year and Month
            $arrYearMonth = explode("-", $yearAndMonth);
            //TODO format Year and Month
            //TODO Plan and name Project
            $projectAndPlan = $this->getProjectAndPlan($arrYearMonth[0], $arrYearMonth[1]);
            //TODO Plan and name Project
            $data = [];
            if (!empty($projectAndPlan)) {
                $data = [
                    'plans' => $projectAndPlan,
                    'status' => 200,
                ];
            } else {
                $data = [
                    'plans' => 0,
                    'status' => 500,
                ];
            }
            return response()->json($data);
        } catch (\Throwable $th) {
            abort(500);
        }
    }
    /**
     * Summary of ProjectAndEmployeeToMonth
     * @param mixed $yearAndMonth
     * @return \Illuminate\Http\JsonResponse
     */
    public function ProjectAndEmployeeToMonth($yearAndMonth)
    {
        try {
            //TODO format Year and Month
            $arrYearMonth = explode("-", $yearAndMonth);
            //TODO format Year and Month
            //TODO project
            $projects = $this->getProjectAndPlan($arrYearMonth[0], $arrYearMonth[1]);
            //TODO project
            //TODO user
            $users = $this->getUsers($arrYearMonth[0], $arrYearMonth[1]);
            //TODO user
            $dayWorks = $this->getDayWorks($arrYearMonth[0], $arrYearMonth[1]);
            $results = [];
            $count = 0;
            foreach ($projects as $project) {
                $arrUsers = [];
                $arrDayWork = [];
                foreach ($users as $user) {
                    if ($project['id'] == $user['project_id']) {
                        $arrUsers[] = [
                            'projectId' => $user['project_id'],
                            'name' => $user['name'],
                            'userId' => $user['id']
                        ];
                        $count++;
                    }
                }
                foreach ($arrUsers as $user) {
                    if ($user['projectId'] == $project['id']) {
                        $dayAndHours = [];
                        foreach ($dayWorks as $dayWork) {
                            if (
                                $user['userId'] == $dayWork['id']
                                and $user['projectId'] == $dayWork['project_id']
                            ) {
                                $dayAndHours[] = [
                                    'day' => (int) Carbon::parse($dayWork['day_work'])->format('d'),
                                    'hours' => $dayWork['working_hours'],
                                    'userId' => $user['userId'],
                                    'projectId' => $user['projectId'],
                                ];
                            }
                        }
                        $arrDayWork[] = [
                            'nameProject' => $project['name'],
                            'name' => $user['name'],
                            'dayWorks' => $dayAndHours,
                        ];
                    }
                }
                $results[] = ['count' => $count, $arrDayWork];
                $count = 0;
            }
            $data = [];
            if (!empty($results)) {
                $data = [
                    'status' => 200,
                    'data' => $results,
                ];
            } else {
                $data = [
                    'status' => 500,
                    'data' => 0,
                ];
            }
            return response()->json($data);
        } catch (\Throwable $th) {
            abort(500);
        }
    }
    /**
     * Summary of getProjectAndPlan
     * @param mixed $year
     * @param mixed $month
     */
    public function getProjectAndPlan($year, $month)
    {
        $project = $this->project
            ->join('plan', 'projects.id', 'plan.project_id')
            ->whereYear("plan.day_addEmp", $year)
            ->whereMonth("plan.day_addEmp", $month)
            ->select('projects.name', DB::raw('sum(plan.plan) as plan'), 'projects.id')
            ->groupBy('projects.name', 'projects.id')
            ->get()->toArray();
        return $project;
    }
    /**
     * Summary of getUsers
     * @param mixed $year
     * @param mixed $month
     * @return mixed
     */
    public function getUsers($year, $month)
    {
        $users = $this->user
            ->join('employee_project', 'users.id', 'employee_project.user_id')
            ->whereYear("employee_project.day_work", $year)
            ->whereMonth("employee_project.day_work", $month)
            ->select('users.name', 'employee_project.project_id', 'users.id')
            ->groupBy('users.name', 'employee_project.project_id', 'users.id')
            ->get()->toArray();
        return $users;
    }
    /**
     * Summary of getDayWorks
     * @param mixed $year
     * @param mixed $month
     * @return mixed
     */
    public function getDayWorks($year, $month)
    {
        $dayWork = $this->employeeProject
            ->join('users', 'employee_project.user_id', 'users.id')
            ->whereYear("employee_project.day_work", $year)
            ->whereMonth("employee_project.day_work", $month)
            ->select(
                'employee_project.day_work',
                'users.id',
                'employee_project.project_id',
                'employee_project.working_hours'
            )
            ->get()->toArray();
        return $dayWork;
    }
    /**
     * Summary of ProjectAndEmployeeToMonthExport
     * @param mixed $yearAndMonth
     * @return array<array>
     */
    public function ProjectAndEmployeeToMonthExport($yearAndMonth)
    {
        try {
            //TODO format Year and Month
            $arrYearMonth = explode("-", $yearAndMonth);
            //TODO format Year and Month
            //TODO project
            $projects = $this->getProjectAndPlan($arrYearMonth[0], $arrYearMonth[1]);
            //TODO project
            //TODO user
            $users = $this->getUsers($arrYearMonth[0], $arrYearMonth[1]);
            //TODO user
            $dayWorks = $this->getDayWorks($arrYearMonth[0], $arrYearMonth[1]);
            $results = [];
            $count = 0;
            foreach ($projects as $project) {
                $arrUsers = [];
                $arrDayWork = [];
                foreach ($users as $user) {
                    if ($project['id'] == $user['project_id']) {
                        $arrUsers[] = [
                            'projectId' => $user['project_id'],
                            'name' => $user['name'],
                            'userId' => $user['id']
                        ];
                        $count++;
                    }
                }
                foreach ($arrUsers as $user) {
                    if ($user['projectId'] == $project['id']) {
                        $dayAndHours = [];
                        foreach ($dayWorks as $dayWork) {
                            if (
                                $user['userId'] == $dayWork['id']
                                and $user['projectId'] == $dayWork['project_id']
                            ) {
                                $dayAndHours[] = [
                                    'day' => (int) Carbon::parse($dayWork['day_work'])->format('d'),
                                    'hours' => $dayWork['working_hours'],
                                    'userId' => $user['userId'],
                                    'projectId' => $user['projectId'],
                                ];
                            }
                        }
                        $arrDayWork[] = [
                            'nameProject' => $project['name'],
                            'name' => $user['name'],
                            'userId' => $user['userId'],
                            'projectId' => $user['projectId'],
                            'dayWorks' => $dayAndHours,
                        ];
                    }
                }
                $results[] = ['count' => $count, $arrDayWork];
                $count = 0;
            }
            return $results;
        } catch (\Throwable $th) {
            abort(500);
        }
    }
}
