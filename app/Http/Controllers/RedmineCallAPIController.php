<?php
namespace App\Http\Controllers;
use App\Models\Position;
use App\Models\Project;
use App\Models\TabletUser;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;
class RedmineCallAPIController extends Controller
{
    protected $users;
    protected $TabletUser;
    protected $projects;
    protected $positions;
    public function __construct()
    {
        $this->users = new User();
        $this->TabletUser = new TabletUser();
        $this->projects = new Project();
        $this->positions = new Position();
    }
    public function index()
    {
        $this->addProjects();
        $this->addPositions();
        session()->flash('success', 'Đồng bộ hóa hệ thống với Redmine thành công!');
        return $this->addUsers();
    }
    /**Add nhân viên */
    public function addUsers()
    {
        try {
            if (Gate::allows('policy', Auth::user())) {
                $arrPasswordWebtablet = [];
                $urlGetTotal = $this->URLRedmine("users", 0);
                $total = Http::get($urlGetTotal)->collect()['total_count'];
                $urlUsers = $this->URLRedmine("users", $total);
                $apiUsers = Http::get($urlUsers)->collect()['users'];
                $count = count($apiUsers);
                // dd($apiUsers);
                for ($i = 0; $i < $count; $i++) {
                    $checkUserIsset = $this->users->where("email", "=", $apiUsers[$i]['mail'])->exists();
                    $AccountJP = "@andtech.co.jp";
                    $getLastAccountJP= explode("@",$AccountJP);
                    $getLastAccountAll=explode("@", $apiUsers[$i]['mail']);
                    if (!$checkUserIsset and $getLastAccountJP[1]!=$getLastAccountAll[1]) {
                        $passord = Str::random(8);
                        $fullname = $apiUsers[$i]['lastname'] . " " . $apiUsers[$i]['firstname'];
                        $this->users->create([
                            'name' => $fullname,
                            'email' => $apiUsers[$i]['mail'],
                            'password' => Hash::make($passord),
                            // 'birthday' => "2000-01-01",
                            // 'phone' => "123456789",
                            // 'address' => "default address",
                            // 'gender' => '1',
                        ]);
                        $idUsersLast = User::select('id')->orderBy('id', 'desc')->first()->id; //id last
                        $idUser = $idUsersLast++;
                        $lastUsers = TabletUser::select('email')->orderBy('email', 'desc')->first()->email; //Mã nhân viên current last
                        $emp_code = $this->emp_code((int) ++$lastUsers); //0005
                        $min = pow(10, 4 - 1);
                        $max = pow(10, 4) - 1;
                        $emp_pin = mt_rand($min, $max);
                        $arrPasswordWebtablet[$i] = [
                            'fullname' => $fullname,
                            'email' => $apiUsers[$i]['mail'],
                            'password' => $passord,
                            'emp_code' => $emp_code,
                            'emp_pin' => $emp_pin
                        ];
                        DB::table("tablet_users")->insert([
                            'email' => $emp_code,
                            'password' => Hash::make($emp_pin),
                            "user_id" => $idUser,
                        ]);
                        if ($apiUsers[$i]['admin'] == true) {
                            DB::table("user_role")->insert([
                                "user_id" => $idUser,
                                'role_id' => 1,
                            ]);
                        } else {
                            DB::table("user_role")->insert([
                                "user_id" => $idUser,
                                'role_id' => 2,
                            ]);
                        }
                        session()->flash('successEmployee', 'Nhân viên');
                    }
                }
                return $this->export($arrPasswordWebtablet);
                // $viewData = [
                //     'arr' => $arrPasswordWebtablet,
                // ];
                // $emailRegister = 'php.laravel.serve@gmail.com';
                // Mail::send('sendEmail', $viewData, function ($email) use ($emailRegister) {
                //     $email->subject('Danh sách tài khoản nhân viên');
                //     $email->to($emailRegister);
                // });
            }
        } catch (\Throwable $th) {
            abort(500);
        }
    }
    public function emp_code($emp_code)
    {
        // $emp_code = $this->employee->getEmpCode();
        $addEmp = $emp_code;
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
    /**Add dự án */
    public function addProjects()
    {
        try {
            if (Gate::allows('policy', Auth::user())) {
                $urlGetTotal = $this->URLRedmine("projects");
                $total = Http::get($urlGetTotal)->collect()['total_count'];
                $urlProjects = $this->URLRedmine("projects", $total);
                $apiProjects = Http::get($urlProjects)->collect()['projects'];
                $count = count($apiProjects);
                for ($i = 0; $i < $count; $i++) {
                    $nameProject = trim($apiProjects[$i]['name']);
                    $checkProjectIsset = $this->projects->where("name", "=", $nameProject)->exists();
                    if (!$checkProjectIsset) {
                        $status = (string) $apiProjects[$i]['status'];
                        $this->projects->create([
                            'name' => $nameProject,
                            'status' => $status,
                            // 'start_project' => Carbon::now()->format("y-m-d"),
                            'description' => $apiProjects[$i]['description'],
                        ]);
                        session()->flash('successProject', 'Dự án');
                    }
                }
            }
        } catch (\Throwable $th) {
            abort(500);
        }
    }
    /**Add positions  */
    public function addPositions()
    {
        try {
            if (Gate::allows('policy', Auth::user())) {
                $urlPositions = $this->URLRedmine("roles");
                $apiPositions = Http::get($urlPositions)->collect()['roles'];
                $count = count($apiPositions);
                for ($i = 0; $i < $count; $i++) {
                    $name = trim($apiPositions[$i]['name']);
                    $checkPositionIsset = $this->positions->where('name', '=', $name)->exists();
                    if (!$checkPositionIsset) {
                        $this->positions->create([
                            'name' => $name,
                            'display_name' => $name,
                        ]);
                        session()->flash('successPosition', 'Vai trò');
                    }
                }
            }
        } catch (\Throwable $th) {
            abort(500);
        }
    }
    /**Add Roles and Employee  */
    // public function EmployeeRole()
    // {
    //     $urlProjects = $this->URLRedmine("projects");
    //     $apiProjects = Http::get($urlProjects)->collect()['projects'];
    //     $arrEmployeeRole = [];
    //     for ($i = 0; $i < count($apiProjects); $i++) {
    //         // echo $project['id'];
    //         $urlProjectsRole = $this->URLRedmine("projects", "", $apiProjects[$i]["id"] . "/memberships.json");
    //         $apiProjectsRole = Http::get($urlProjectsRole)->collect()['memberships'];
    //         dd($apiProjectsRole);
    //         $arrEmployeeRole[$i] = $apiProjects[$i]['id'];
    //         $arrEmployeeRole[$i]['idEmployee'] = $apiProjectsRole['roles'];
    //     }
    // }
    public function URLRedmine($name, $limit = 0, $details = null)
    {
        if ($limit == 0 and $details == null) {
            $url = config("app.redmine_url") . "$name.json?&key=" . config("app.redmine_key_admin");
        }
        if ($details == null and $limit > 0) {
            $url = config("app.redmine_url") . "$name.json?limit=$limit&key=" . config("app.redmine_key_admin");
        }
        if ($details != null and $limit == 0) {
            $url = config("app.redmine_url") . "$name/$details?&key=" . config("app.redmine_key_admin");
        }
        return $url;
    }
    /**Export Excel */
    public function export($arrPasswordWebtablet)
    {
        if (count($arrPasswordWebtablet) > 0) {
            return Excel::download(new UsersExport($arrPasswordWebtablet), 'List-users.xlsx');
        } else {
            return redirect()->route('dashboard.get');
        }
    }
}
