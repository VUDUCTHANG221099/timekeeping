<?php
namespace App\Repositories;

use App\Models\TabletUser;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EmployeeRepository
{
    protected $user, $tabletUser;
    public function __construct()
    {
        $this->user = new User();
        $this->tabletUser = new TabletUser();
    }
    public function all()
    {
        return $this->user->orderBy("id", "asc")->get();
    }
    public function countEmp()
    {
        return $this->user->where("id", "!=", Auth::user()->id)->count();
    }
    public function create($fullname, $email, $password, $emp_code, $emp_pin)
    {
        // dd($salary);
        $employee = new User();
        $employee->create([
            'name' => Str::of($fullname)->trim(),
            'email' => Str::of($email)->trim(),
            'password' => Hash::make(Str::of($password)->trim()),
            // "birthday" => $birthday,
            // "phone" => Str::of($phone)->trim(),
            // "address" => Str::of($address)->trim(),
            // "gender" => $gender,
            // "salary"=>Str::of($salary)->trim(),
            // "emp_code" => Str::of($emp_code)->trim(),
            // "emp_pin" => Hash::make(Str::of($emp_pin)->trim()),
        ]);
        DB::table("tablet_users")->insert([
            "email" => Str::of($emp_code)->trim(),
            "password" => Hash::make(Str::of($emp_pin)->trim()),
            "user_id" => $this->getId(),
        ]);
        // $TabletUser=new TabletUser();
        // $TabletUser->create([
        //     "email" => Str::of($emp_code)->trim(),
        //     "password" => Hash::make(Str::of($emp_pin)->trim()),
        //     "user_id"=>$this->getId(),
        // ]);
    }
    public function destroy($id)
    {
        $this->user->where("id", $id)->delete();
    }
    public function getEmpCode()
    {
        return $this->tabletUser->select('email')->orderBy('email', 'desc')->first()->email;
    }
    public function getId()
    {
        return $this->user->select('id')->orderBy('id', 'desc')->first()->id;
    }
    public function getEmployee($id)
    {
        return $this->user->where("id", $id)->with("roles", "positions")->first();
    }
    public function update($id, $fullname, $status)
    //$birthday, $phone, $address, $gender,$salary

    {
        $this->user->where("id", $id)->update([
            "name" => Str::of($fullname)->trim(),
            "status" => $status,
            // "phone" => Str::of($phone)->trim(),
            // "address" => Str::of($address)->trim(),
            // "gender" => $gender,
            // "salary" => $salary,
        ]);
    }
    public function ChangePassword($id, $password)
    {
        $this->user->where("id", $id)->update(["password" => Hash::make(Str::of($password)->trim())]);
    }
    public function getEmployeeAPI()
    {
        return $this->user->select("users.id as value", "users.name")->where("user_role.role_id", "2")->join("user_role", "users.id", "user_role.user_id")->get();
    }
    public function GetEmpPro($id)
    { //Add % on the project
        return $this->user->where('id', $id)->with('projects')->first();
    }
    public function UpdatePlan($employee, $percent, $project)
    {
        foreach ($percent as $key => $val) {
            DB::table('plan')->where(["user_id" => $employee, "project_id" => $project[$key]])->update([
                'plan' => $percent[$key],
            ]);
        }
    }
}
