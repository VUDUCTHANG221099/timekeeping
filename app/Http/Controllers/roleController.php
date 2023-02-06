<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use App\Models\Role;
use App\Repositories\RoleRepository;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class roleController extends Controller
{
    /**
     * Functions index=>View list Role
     * Functions create =>Add Role [viewCreate|create]
     * Functions update=>Update Role [viewUpdate|update]
     * Functions destroy=>Destroy Role
     */
    protected $role;
    public function __construct(RoleRepository $roleRepository)
    {
        $this->role = $roleRepository;
    }
    public function index()
    {
        try {
            if (Gate::allows('policy', Auth::user())) {
                $user_role=DB::table("user_role")->distinct()->pluck('role_id')->toArray();

                $data = [
                    'role' => $this->role->all(),
                    'user_role'=>$user_role,
                ];
                return view('role', $data);
            }
        } catch (\Throwable $th) {
            abort(500);
        }
    }
    public function viewCreate()
    {
        try {
            if (Gate::allows('policy', Auth::user())) {
                return view("role.add_update");
            }
        } catch (\Throwable $th) {
            abort(500);
        }
    }
    public  function  create(RoleRequest  $request)
    {
        try {
            if (Gate::allows('policy', Auth::user())) {
                $data = [
                    'nameRole' => $request->nameRole,
                    "display_name" => $request->display_name,
                ];
                $this->role->create($data['nameRole'], $data['display_name']);
                session()->flash('success', 'Thêm thành công!');
                return redirect()->route('role.get.all');
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
                    'role' => $this->role->getRole($id),
                ];
                return view("role.add_update", $data);
            }
        } catch (\Throwable $th) {
            abort(500);
        }
    }
    public function update(RoleRequest $request)
    {
        try {
            if (Gate::allows('policy', Auth::user())) {
                $this->role->update($request->id, $request->display_name);
                session()->flash('success', 'Cập nhật thành công!');
                return redirect()->route('role.get.all');
            }
        } catch (\Throwable $th) {
            abort(500);
        }
    }
    public function destroy($id)
    {
        try {
            if (Gate::allows('policy', Auth::user())) {
                $this->role->destroy($id);
                session()->flash('success', 'Xóa thành công!');
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            abort(500);
        }
    }
}
