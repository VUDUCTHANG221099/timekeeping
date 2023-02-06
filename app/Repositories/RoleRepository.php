<?php

namespace App\Repositories;

use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class RoleRepository
{
    public function all()
    {
        return Role::all();
    }
    public function create($nameRole, $display_name)
    {
        $role = new Role();
       
        $role->create([
            'name' => Str::of($nameRole)->trim(),
            'display_name' => Str::of($display_name)->trim(),
        ]);
    }
    public function getRole($id)
    {
        return Role::where('id', $id)->first();
        
    }
    public function update($id, $display_name)
    {
        Role::where('id', $id)->update([
            'display_name' => Str::of($display_name)->trim(),
        ]);
    }
    public function destroy($id){
        Role::where('id', $id)->delete();
    }
    public function createUser_role($user_id, $roles)
    {
        if (is_array($roles)) {

            foreach ($roles as $key => $value) {
                DB::table('user_role')->insert([
                    'user_id' => $user_id,
                    'role_id' => $value,
                ]);
            }
        } else {
            DB::table('user_role')->insert([
                'user_id' => $user_id,
                'role_id' => $roles,
            ]);
        }
    }
    public function updateUser_role($user_id, $roles)
    {
        if (is_array($roles)) {
            DB::table('user_role')->where('user_id', $user_id)->delete();
            foreach ($roles as $key => $value) {
                DB::table('user_role')->insert([
                    'user_id' => $user_id,
                    'role_id' => $value,
                ]);
            }
        } else {
            DB::table("user_role")->where("user_id", $user_id)->update([
                "role_id" => $roles,
            ]);
        }
    }
}
