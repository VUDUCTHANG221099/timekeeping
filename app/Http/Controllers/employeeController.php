<?php
namespace App\Http\Controllers;

use App\Http\Requests\EmployeeRequest;
use App\Http\Requests\PassConfirmationRequest;
use App\Models\TabletUser;
use App\Models\User;
use App\Repositories\EmployeeRepository;
use App\Repositories\PositionRepository;
use App\Repositories\RoleRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class employeeController extends Controller
{
    /**
     * Functions index=>View list Employee
     * Functions create =>Add employee [viewCreate|create]
     * Functions update=>Update employee [viewUpdate|update]
     * Functions destroy=>Destroy employee
     * Functions emp_code=>Create employee code
     * Functions changePassword|viewchangdPassword=>Change Password
     * Functions percent=> add %  Project [ViewPercent|percent]// Bỏ
     * Functions viewDetal|addPercent
     */
    protected $employee;
    protected $role;
    protected $position;
    protected $user;
    protected $tabletUser;
    public function __construct(
        EmployeeRepository $employeeRepository,
        RoleRepository $roleRepository,
        PositionRepository $positionRepository
    )
    {
        $this->employee = $employeeRepository;
        $this->role = $roleRepository;
        $this->position = $positionRepository;
        $this->user = new User();
        $this->tabletUser = new TabletUser();
    }
    public function index()
    {
        try {
            if (Gate::allows('policy', Auth::user())) {
                $data = [
                    'checkUserIsLogin' => $this->user->whereId(Auth::user()->id)->pluck('id')->toArray(),
                    'employee' => $this->employee->all(),
                ];
                return view("employee", $data);
            }
        } catch (\Throwable $th) {
            abort(500);
        }
    }
    public function viewCreate()
    {
        try {
            if (Gate::allows('policy', Auth::user())) {
                $data = [
                    "roles" => $this->role->all(),
                    'positions' => $this->position->all(),
                    "emp_code" => $this->emp_code(),
                ];
                return view("employee.add_update", $data);
            }
        } catch (\Throwable $th) {
            abort(500);
        }
    }
    public function create(EmployeeRequest $request)
    {
        try {
            if (Gate::allows('policy', Auth::user())) {
                $data = [
                    "fullname" => $request->fullname,
                    "email" => $request->email,
                    "password" => $request->password,
                    // "birthday" => $request->birthday,
                    // "gender" => $request->gender,
                    // "phone" => $request->phone,
                    // "address" => $request->address,
                    "roles" => $request->roles,
                    // "positions" => $request->positions,
                    // "salary" => $request->salary,
                    "emp_code" => $this->emp_code(),
                    "emp_pin" => $this->emp_code(),
                ];
                $this->employee->create(
                    $data['fullname'],
                    $data['email'],
                    $data['password'],
                    // $data['birthday'],
                    // $data['phone'],
                    // $data['address'],
                    // $data['gender'],
                    // $data['salary'],
                    $data['emp_code'],
                    $data['emp_pin']
                ); //Add User
                $user_id = $this->employee->getId(); //Get Id
                $this->role->createUser_role($user_id, $data['roles']); //Add Role
                // $this->position->createUser_position($user_id, $data['positions']); //Add Position
                session()->flash('success', 'Thêm thành công!');
                return redirect()->route("employee.get.all");
            }
        } catch (\Throwable $th) {
            abort(500);
        }
    }
    public function viewUpdate($id)
    {
        try {
            if (Gate::allows('policy', Auth::user())) {
                $data = [
                    "roles" => $this->role->all(),
                    'positions' => $this->position->all(),
                    "employee" => $this->employee->getEmployee($id),
                ];
                return view("employee.add_update", $data);
            }
        } catch (\Throwable $th) {
            abort(500);
        }
    }
    public function update(EmployeeRequest $request)
    {
        try {
            if (Gate::allows('policy', Auth::user())) {
                $data = [
                    'id' => $request->id,
                    "fullname" => $request->fullname,
                    // "birthday" => $request->birthday,
                    // "gender" => $request->gender,
                    // "phone" => $request->phone,
                    // "address" => $request->address,
                    "roles" => $request->roles,
                    "status" => $request->status,
                    // 'salary' => $request->salary,
                ];
                $this->employee->update(
                    $data['id'],
                    $data['fullname'],
                    $data['status'],
                    // $data['phone'],
                    // $data['address'],
                    // $data['gender'],
                    // $data['salary']
                );
                $this->role->updateUser_role($data['id'], $data['roles']);
                // $this->position->updateUserPosition($data['id'], $data['positions']);
                session()->flash('success', 'Cập nhật thành công!');
                return redirect()->route("employee.get.all");
            }
        } catch (\Throwable $th) {
            abort(500);
        }
    }
    public function destroy($id)
    {
        try {
            if (Gate::allows('policy', Auth::user())) {
                $this->employee->destroy($id);
                /**Remove user_role/user_position/tablet_users */
                DB::table('user_role')->where("user_id", $id)->delete();
                DB::table('user_position')->where("user_id", $id)->delete();
                DB::table('tablet_users')->where("user_id", $id)->delete();
                /**Remove user_role/user_position/tablet_users */
                session()->flash('success', 'Xóa thành công!');
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            abort(500);
        }
    }
    public function viewchangdPassword($id)
    {
        try {
            // if (Gate::allows('policy', Auth::user())) {
            $data = [
                "employee" => $this->employee->getEmployee($id),
            ];
            return view("employee.changePassword", $data);
            // }
        } catch (\Throwable $th) {
            abort(500);
        }
    }
    public function changePassword(PassConfirmationRequest $request)
    {
        try {
            // if (Gate::allows('policy', Auth::user())) {
            if (Auth::user()->id == $request->id) {
                $this->employee->changePassword($request->id, $request->password);
                Auth::logout();
                return redirect()->route("login.get");
            }
            $this->employee->changePassword($request->id, $request->password);
            session()->flash('success', 'Đổi mật khẩu thành công!');
            return redirect()->route("employee.get.all");
            // }
        } catch (\Throwable $th) {
            abort(500);
        }
    }
    public function emp_code()
    {
        $emp_code = $this->employee->getEmpCode();
        $addEmp = $emp_code + 1;
        // dd($addEmp);
        if ($addEmp < 10) {
            $addEmp = "000$addEmp";
        }
        if ($addEmp > 9 and $addEmp < 100) {
            $addEmp = "00$addEmp";
        }
        if ($addEmp > 99 and $addEmp < 1000) {
            $addEmp = "0$addEmp";
        }
        if ($addEmp > 1000) {
            $addEmp = "$addEmp";
        }
        return $addEmp;
    }
    public function viewDetal($id)
    {
        try {
            if (Gate::allows('policy', Auth::user())) {
                $data = [
                    'employee' => $this->employee->getEmployee($id),
                    'emp_code' => $this->tabletUser->where("user_id", $id)->first(),
                    'project' => $this->user->where("id", $id)->with("projects")->first(),
                ];
                return view('employee.detail', $data);
            }
        } catch (\Throwable $th) {
            abort(500);
        }
    }
    public function addPercent(Request $request)
    {
        try {
            if (Gate::allows('policy', Auth::user())) {
                $arrYearAndMonth = explode("/", $request->month);
                for ($i = 0; $i < count($request->project); $i++) {
                    if ($request->percent[$i] < 0) {
                        $request->session()->flash('error', "Không được để giá trị âm!");
                        break;
                    } else {
                        $data = ['plan' => $request->percent[$i]];
                        DB::table('plan')
                            ->whereMonth("day_addEmp", $arrYearAndMonth[1])
                            ->whereYear("day_addEmp", $arrYearAndMonth[0])
                            ->where(["project_id" => $request->project[$i], "user_id" => $request->id])
                            ->update($data);
                        $request->session()->flash('success', "Cập nhật phần trăm thành công!");
                    }
                }
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            abort(500);
        }
    }
}
