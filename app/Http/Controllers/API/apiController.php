<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\DayOff;
use App\Models\employeeProject;
use App\Models\Plan;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class apiController extends Controller
{
    /**
     * Functions listEmployee;=>List Employee
     * Functions listProject;=>List Project =>Status=1
     * Functions listMonthlyProject=>Status= 1
     */
    protected $plan;
    protected $user;
    protected $employeeProject;
    protected $project;
    protected $dayOff;
    public function __construct()
    {
        $this->plan = new Plan();
        $this->user = new User();
        $this->employeeProject = new employeeProject();
        $this->project = new Project();
        $this->dayOff = new DayOff();
    }
    /**
     * Summary of listProject
     * @param mixed $dayWork
     * @return \Illuminate\Http\JsonResponse
     * @description Use in tablet checkout
     * @author Vũ Đức Thắng
     */
    public function listProject($dayWork)
    {
        try {
            $user = $this->user->where('tablet_users.id', Auth::guard("tablet_users")->id())
                ->join("tablet_users", "tablet_users.user_id", "users.id")
                ->select("users.id as id")->first()->id;
            //#TODO Checkout Day Off
            $dayOff = $this->dayOff
                ->whereYear("day_work", Carbon::parse($dayWork)->format("Y"))
                ->whereMonth("day_work", Carbon::parse($dayWork)->format("m"))
                ->whereDay("day_work", Carbon::parse($dayWork)->format("d"))
                ->where(["user_id" => $user])->select('status');
            // dd($dayOff->exists());
            $statusDayOff = 0;
            if ($dayOff->exists()) {
                $statusDayOff = $dayOff->first()->status;
            } else {
                $statusDayOff = 0;
            }
            //#TODO Checkout Day Off
            $employeeProject = $this->employeeProject->where(['day_work' => $dayWork, 'user_id' => $user])
                // ->join("projects", "projects.id", "employee_project.project_id")
                ->get()->toArray();
            $data = [
                'projects' => Project::where('status', "1")->get(),
                'dayOff' => $statusDayOff,
                'employeeProject' => $employeeProject,
                'status' => 200
            ];
            return response()->json($data);
        } catch (\Throwable $th) {
            abort(500);
        }
    }
    /**
     * Summary of listMonthlyProject
     * @param mixed $month
     * @return \Illuminate\Http\JsonResponse
     * @description Use in dashboard and report of employee
     * @author Vũ Đức Thắng
     */
    public function listMonthlyProject($month)
    {
        try {
            // if (!Gate::allows('policy', Auth::user())) {
            $arrYearAndMonth = explode("-", $month); //Gets
            $listProject = DB::table('employee_project')
                ->whereMonth('day_work', '=', $arrYearAndMonth[1])
                ->whereYear('day_work', '=', $arrYearAndMonth[0])
                ->where('user_id', Auth::user()->id)
                ->join('projects', 'projects.id', 'employee_project.project_id')
                ->distinct()
                ->select('projects.name as namePorject', 'projects.id as idProject', 'projects.status as statusPorject')->get()->toArray();
            $listDays = DB::table('employee_project')
                ->whereMonth('day_work', '=', $arrYearAndMonth[1])
                ->whereYear('day_work', '=', $arrYearAndMonth[0])
                ->where('user_id', Auth::user()->id)
                ->join('projects', 'projects.id', 'employee_project.project_id')
                ->select('employee_project.project_id as idProject', 'employee_project.day_work', 'employee_project.working_hours')->get()->toArray();
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
                                'nameProject' => $listProject[$i]->namePorject,
                                'idProject' => $listProject[$i]->idProject,
                                'days' => $daysAndHours
                            ];
                        }
                    }
                    $daysAndHours = [];
                }
                return response()->json(['monthlyProject' => $list, 'status' => 200]);
            }
            return response()->json(['monthlyProject' => 0, 'status' => 200]);
            // }
        } catch (\Throwable $th) {
            abort(500);
        }
    }
    /**
     * Summary of countProject
     * @param mixed $dayWork
     * @return \Illuminate\Http\JsonResponse
     * @description Use in tablet checkout
     * @author Vũ Đức Thắng
     */
    public function countProject($dayWork)
    {
        try {
            $user = $this->user->where('tablet_users.id', Auth::guard("tablet_users")->id())
                ->join("tablet_users", "tablet_users.user_id", "users.id")
                ->select("users.id as id")->first()->id;
            $Project = $this->employeeProject->where(['day_work' => $dayWork, 'user_id' => $user]);
            return response()->json(['countProject' => $Project->count(), 'status' => 200]);
        } catch (\Throwable $th) {
            abort(500);
        }
    }
    /**
     * Summary of planAndReality
     * @param mixed $month
     * @return \Illuminate\Http\JsonResponse
     * @description Use in dashboard and report of employee
     * @author Vũ Đức Thắng
     */
    public function planAndReality($month)
    {
        try {
            // if (!Gate::allows('policy', Auth::user())) {
            $arrYearAndMonth = explode("-", $month); //Gets
            $user = Auth::user()->id;
            $plan = $this->plan
                ->where(['user_id' => $user])
                ->whereMonth('plan.day_addEmp', '=', $arrYearAndMonth[1])
                ->whereYear('plan.day_addEmp', '=', $arrYearAndMonth[0])
                ->get();
            //Get plan
            $planReality = [];
            foreach ($plan as $projectId) {
                $project = $this->project->where('id', '=', $projectId->project_id)->first();
                $planReality[] = [
                    'nameProject' => $project->name,
                    'plan' => $projectId->plan,
                    'project_id' => $projectId->project_id,
                ];
            }
            //Get plan
            // dd($planReality);
            // ->join('projects', 'projects.id', 'plan.project_id')
            // ->select('projects.name as nameProject', 'plan.*')
            if (isset($planReality) && count($planReality) > 0) {
                return response()->json(['planReality' => $planReality, 'status' => 200]);
            }
            return response()->json(['planReality' => 0, 'status' => 200]);
            // }
        } catch (\Throwable $th) {
            abort(500);
        }
    }
    /**
     * Summary of listPlanOfEmployee detal of employee
     * @param mixed $month
     * @return \Illuminate\Http\JsonResponse
     */
    public function listPlanOfEmployee($month)
    {
        try {
            if (Gate::allows('policy', Auth::user())) {
                $arrYearAndMonth = explode("-", $month);
                $plan = DB::table('plan')->where('user_id', $arrYearAndMonth[2])
                    ->whereMonth('day_addEmp', $arrYearAndMonth[1])
                    ->whereYear('day_addEmp', $arrYearAndMonth[0])->get()->pluck('project_id')->toArray();
                if (count($plan) > 0) {
                    $project_id = [];
                    for ($i = 0; $i < count($plan); $i++) {
                        array_push($project_id, $plan[$i]);
                    }
                    $listProject = [];
                    foreach ($project_id as $key => $value) {
                        $project = Project::where(["projects.id" => $value, 'projects.status' => "1"])
                            ->whereMonth('plan.day_addEmp', $arrYearAndMonth[1])
                            ->whereYear('plan.day_addEmp', $arrYearAndMonth[0])
                            ->where("plan.user_id", $arrYearAndMonth[2])
                            ->join('plan', 'projects.id', 'plan.project_id')->first();
                        if ($project) {
                            $listProject[] = ['name' => $project->name, 'id' => $project->project_id, 'plan' => $project->plan];
                        }
                    }
                    return response()->json(['listProject' => $listProject, 'status' => 200]);
                }
                return response()->json(['listProject' => "Không có dự án", 'status' => 404]);
            }
        } catch (\Exception $e) {
            abort(500);
        }
    }
    /**API count Employee join on project and list Employee and delete plan*/
    /**
     * Summary of employeeAndProjectCount
     * @param mixed $data
     * @return \Illuminate\Http\JsonResponse
     * @description count number employee
     */
    public function employeeAndProjectCount($data)
    {
        try {
            $yearAndMonthAndIdProject = explode('-', $data);
            $countAndListEmployee = $this->plan->
                where(['project_id' => $yearAndMonthAndIdProject[2]])
                /*  $countAndListEmployee = DB::table('plan')
                ->join('users', 'plan.user_id', 'users.id')
                ->where('project_id', $yearAndMonthAndIdProject[2])*/
                ->whereMonth('day_addEmp', $yearAndMonthAndIdProject[1])
                ->whereYear('day_addEmp', $yearAndMonthAndIdProject[0]);
            $getUserId = $countAndListEmployee->select('user_id')->get();
            /**Get User of project */
            $users = [];
            foreach ($getUserId as $userId) {
                $user = $this->user->where(['id' => $userId->user_id])->first()->toArray();
                if ($user['status'] == '1') {
                    $users[] =
                        [
                            'name' => $user['name'],
                            'id' => $user['id']
                        ];
                } else {
                    $users = [];
                }
            }
            /**Get User of project */
            // dd(($users));
            // join('users', 'plan.user_id', 'users.id')
            //     ->where(['project_id'=>$yearAndMonthAndIdProject[2]])
            //     ->whereMonth('day_addEmp', $yearAndMonthAndIdProject[1])
            //     ->whereYear('day_addEmp', $yearAndMonthAndIdProject[0]);
            // dd($countAndListEmployee->user->toArray());
            $showdataOnView = [
                'countEmployee' => count($users),
                'listEmployee' => $users,
                /* 'countEmployee' => $countAndListEmployee->count(),
                'listEmployee' => $countAndListEmployee->select('name', 'users.id', 'plan')->get(),*/
                'status' => 200,
            ];
            return response()->json($showdataOnView);
        } catch (\Throwable $th) {
            abort(500);
        }
    }
    /**
     * Summary of removeEmployeePlan
     * @param mixed $data
     * @return \Illuminate\Http\JsonResponse
     * @description Remove employee in project
     */
    public function removeEmployeePlan($data)
    {
        try {
            $yearAndMonthAndIdProject = explode('-', $data);
            DB::table('plan')->where([
                'project_id' => $yearAndMonthAndIdProject[2],
                'user_id' => $yearAndMonthAndIdProject[3]
            ])
                ->whereMonth('day_addEmp', $yearAndMonthAndIdProject[1])
                ->whereYear('day_addEmp', $yearAndMonthAndIdProject[0])->delete();
            $showdataOnView = [
                'status' => 200,
            ];
            return response()->json($showdataOnView);
        } catch (\Throwable $th) {
            abort(500);
        }
    }
    /**
     * Summary of listProjectOfEmployeeMonth employee details
     * @param mixed $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function listProjectOfEmployeeMonth($data)
    {
        try {
            $yearAndMonthAndIdEmployee = explode('-', $data);
            $listProjectOfEmployeeMonth = $this->employeeProject
                ->join('projects', 'projects.id', 'employee_project.project_id')
                ->where(['employee_project.user_id' => $yearAndMonthAndIdEmployee[2]])
                ->whereMonth('employee_project.day_work', $yearAndMonthAndIdEmployee[1])
                ->whereYear('employee_project.day_work', $yearAndMonthAndIdEmployee[0])
                ->select('projects.name', 'working_hours', 'day_work')
                ->get();
            return response()->json(['listProjectOfEmployeeMonth' => $listProjectOfEmployeeMonth, 'status' => 200]);
        } catch (\Throwable $th) {
            abort(500);
        }
    }
}
