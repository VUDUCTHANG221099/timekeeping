<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles=[
            [
                'name'=>"admin",
                'display_name'=>"Quản trị viên",
            ],
            [
                'name'=>"user",
                'display_name'=>"Nhân viên",
            ],
        ];
        $role=new Role();
        foreach ($roles as $key =>$value){
            $role->create([
               'name'=>$roles[$key]['name'],
               'display_name'=>$roles[$key]['display_name'],
            ]);
        }
        $user_role=[
            [
                'user_id'=>1,
                'role_id'=>1,
            ],
            [
                'user_id'=>2,
                'role_id'=>1,
            ],
            [
                'user_id'=>3,
                'role_id'=>2,
            ],
            [
                'user_id'=>4,
                'role_id'=>1,
            ],
        ];
        DB::table("user_role")->insert($user_role);
    }
}
