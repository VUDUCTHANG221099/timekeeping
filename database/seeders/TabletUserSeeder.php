<?php

namespace Database\Seeders;

use App\Models\TabletUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TabletUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data=[
            [
                "email"=>"0001",
                "password"=>Hash::make("0001"),
                "user_id"=>1,
            ],
            [
                "email"=>"0002",
                "password"=>Hash::make("0002"),
                "user_id"=>2,
            ],
            [
                "email"=>"0003",
                "password"=>Hash::make("0003"),
                "user_id"=>3,
            ],
            [
                "email"=>"0004",
                "password"=>Hash::make("0004"),
                "user_id"=>4,
            ],
           
        ];
        DB::table("tablet_users")->insert($data);
        // $TabletUser=new TabletUser();
        // foreach ($data as $key => $value) {
        //     $TabletUser->create([
        //         'email' => $data[$key]['email'],
        //         'password' => $data[$key]['password'],
        //         "user_id"=>$data[$key]['user_id'],
        //     ]);
        // };
    }
}
