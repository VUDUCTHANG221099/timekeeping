<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProjectRequest;
use App\Models\Position;
use App\Models\Project;
use App\Models\User;
use App\Repositories\EmployeeRepository;
use App\Repositories\ProjectRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class projectController extends Controller
{
    /**
     * Functions index=>View list Project
     * Functions create =>Add Project [viewCreate|create]
     * Functions update=>Update Project [viewUpdate|update]
     * Functions destroy=>Destroy Project
     * Functions addEmployee=>On Project [viewAddEmpProject|AddEmpProject]//Bỏ
     * Functions viewDetal|addEmployee
     */
    protected $projects, $employee, $project;
    public function __construct(
        ProjectRepository $projectRepository,
        EmployeeRepository $employeeRepository
    )
    {
        $this->projects = $projectRepository;
        $this->employee = $employeeRepository;
        $this->project = new Project();
    }
    public function index()
    {
        try {
            if (Gate::allows('policy', Auth::user())) {
                $listProject = $this->projects->all();
                $data = [
                    'listProject' => $listProject,
                ];
                return view("project", $data);
            }
        } catch (\Throwable $th) {
            abort(500);
        }
    }
    public function viewCreate()
    {
        try {
            if (Gate::allows('policy', Auth::user())) {
                return view("project.add_update");
            }
        } catch (\Throwable $th) {
            abort(500);
        }
    }
    public function create(ProjectRequest $request)
    {
        try {
            if (Gate::allows('policy', Auth::user())) {
                $this->projects->create($request->all());
                session()->flash('success', 'Thêm thành công!');
                return redirect()->route('project.get.all');
            }
        } catch (\Throwable $th) {
            abort(500);
        }
    }
    public function viewUpdate($id)
    {
        try {
            if (Gate::allows('policy', Auth::user())) {
                $project = $this->projects->getProject($id);
                $data = [
                    'project' => $project,
                ];
                return view('project.add_update', $data);
            }
        } catch (\Throwable $th) {
            abort(500);
        }
    }
    public function update(ProjectRequest $request)
    {
        try {
            if (Gate::allows('policy', Auth::user())) {
                $data = [
                    'id' => $request->id,
                    'name_project' => $request->name,
                    // 'start_project' => $request->start_project,
                    // 'end_project' => $request->end_project,
                    'description' => $request->description,
                    'status' => $request->status,
                    // 'menber' => $request->menber,
                    // 'cost' => $request->cost,
                ];
                $this->projects->update(
                    $data['id'],
                    $data['name_project'],
                    // $data['start_project'],
                    // $data['end_project'],
                    $data['description'],
                    $data['status'],
                    // $data['menber'],
                    // $data['cost'],
                );
                session()->flash('success', 'Cập nhật thành công!');
                return redirect()->route('project.get.all');
            }
        } catch (\Throwable $th) {
            abort(500);
        }
    }
    public function destroy($id)
    {
        try {
            if (Gate::allows('policy', Auth::user())) {
                DB::table('plan')->where('project_id', $id)->delete();
                DB::table('employee_project')->where('project_id', $id)->delete();
                $this->projects->destroy($id);
                session()->flash('success', 'Xóa thành công!');
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            abort(500);
        }
    }
    public function viewDetal($id)
    {
        try {
            if (Gate::allows('policy', Auth::user())) {
                $getProject = $this->project->find($id);
                $data = ['getProject' => $getProject];
                return view('project.detail', $data);
            }
        } catch (\Throwable $th) {
            abort(500);
        }
    }
    public function getEmployeeAPI() //Bỏ

    {
        return response()->json(['employee' => $this->employee->getEmployeeAPI()]);
    }
    /**
     * Thêm nhân viên vào dự án
     * id dự án list id nhân viên
     *
     */
    public function addEmployee(Request $request)
    {
        try {
            // dd($request->all());
            if (Gate::allows('policy', Auth::user())) {
                $listPlan = DB::table('plan')->where('project_id', $request->idProject)
                    ->distinct()->select('user_id')->get();
                $arrPlan = [];
                for ($i = 0; $i < count($listPlan); $i++) {
                    $arrPlan[$i] = (string) $listPlan[$i]->user_id;
                }
                $listEmployee = $request->employee;
                $idProject = $request->idProject;
                if (empty($listEmployee)) {
                    session()->flash('error', 'Bạn chưa chọn nhân viên!');
                    DB::table('plan')->where('project_id', $idProject)->delete();
                    return back();
                } else {
                    for ($i = 0; $i < count($listEmployee); $i++) {
                        if (!in_array($listEmployee[$i], $arrPlan)) {
                            $data = [
                                'status_plan' => '1',
                                'day_addEmp' => Carbon::now(),
                                'user_id' => $listEmployee[$i],
                                'project_id' => $idProject
                            ];
                            DB::table('plan')->insert($data);
                        } else {
                            $check = array_diff($arrPlan, $listEmployee);
                            if (count($check) > 0) {
                                foreach ($check as $key => $value) {
                                    DB::table('plan')->where('project_id', '=', $idProject)->where('user_id', '=', (int) $value)->delete();
                                }
                            }
                        }
                    }
                    session()->flash('success', 'Thêm nhân viên vào dự án thành công!');
                    return back();
                }
            }
        } catch (\Throwable $th) {
            abort(500);
        }
    }
    public function listEmployees($id, $name)
    {
        $listEmp = User::orderBy("name")->where(['status' => '1'])
                // ->join("user_role", "user_role.user_id", "users.id")
            ->get();
        $listRole = Position::all();
        $plans = DB::table('plan')->where('project_id', $id)
            ->where("roles", "!=", null)->whereMonth("day_addEmp", Carbon::now('m'))
            ->whereYear('day_addEmp', Carbon::now('y'))
            ->select('roles', 'user_id')->get();
        $userRoles = $plans->mapWithKeys(function ($plan) {
            return [$plan->user_id => $plan->roles ? json_decode($plan->roles) : []];
        });
        // dd($userRoles);
        $data = [
            'id' => $id,
            'name' => $name,
            'listEmp' => $listEmp,
            'listRole' => $listRole,
            'userRoles' => $userRoles,
        ];
        return view("project.employeeRole", $data);
    }
    public function addEmpoyeeOnProject(Request $request)
    {
        $employeeRoles = $request->input('roles');
        $idProject = $request->idProject;
        $listEmployee = User::all();
        $arr = [];
        foreach ($listEmployee as $employee) {
            $roles = !empty($employeeRoles[$employee->id]) ? $employeeRoles[$employee->id] : [];
            if (count($roles)) {
                $arr[] = [
                    'id' => $employee->id,
                    'roles' => $roles
                ];
            }
        }
        DB::table("plan")->where('project_id', "=", $idProject)->where("roles", "!=", null)->delete();
        foreach ($arr as $item) {
            $employee = $item['id'];
            $roles = json_encode($item['roles']);
            DB::table('plan')->insert([
                'user_id' => $employee,
                'project_id' => $idProject,
                'roles' => $roles,
                'status_plan' => "1",
                'day_addEmp' => Carbon::now(),
            ]);
        }
        return redirect()->route('project.get.detail', ['id' => $request->idProject]);
    }
}
