<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
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
                'name'=>"NghtIT",
                'email'=>"nghtit@vaixgroup.com",
                'password'=>"!VaixGroup1",
                'birthday'=>"1995-01-01",
                'phone'=>"0123456789",
                'address'=>"Japan, Nhật Bản",
                'gender'=>"1",
                'emp_code'=>"0001",
                'emp_pin'=>"0001",

            ],
            [
                'name'=>"Duy Khương",
                'email'=>"duykhuong@vaixgroup.com",
                'password'=>"!VaixGroup1",
                'birthday'=>"1995-01-01",
                'phone'=>"0123456789",
                'address'=>"Hà Nội, Việt Nam",
                'gender'=>"1",
                'emp_code'=>"0002",
                'emp_pin'=>"0002",

            ],
            [
                'name'=>"Vũ Đức Thắng",
                'email'=>"thangvd@vaixgroup.com",
                'password'=>"!VaixGroup1",
                'birthday'=>"1999-10-22",
                'phone'=>"0123456789",
                'address'=>"Hà Nội, Việt Nam",
                'gender'=>"1",
                'emp_code'=>"0003",
                'emp_pin'=>"0003",

            ],
            [
                'name'=>"Nguyễn Thị Thủy",
                'email'=>"thuynt@vaixgroup.com",
                'password'=>"!VaixGroup1",
                'birthday'=>"1999-09-24",
                'phone'=>"0123456789",
                'address'=>"Hà Nội, Việt Nam",
                'gender'=>"0",
                'emp_code'=>"0004",
                'emp_pin'=>"0004",
            ],

        ];
        $user=new User();
        foreach ($data as $key =>$value){
            $user->create([
                'name'=>$data[$key]['name'],
                'email'=>$data[$key]['email'],
                'password'=>Hash::make($data[$key]['password']),
                // 'birthday'=>$data[$key]['birthday'],
                // 'phone'=>$data[$key]['phone'],
                // 'address'=>$data[$key]['address'],
                // 'gender'=>$data[$key]['gender'],
                // 'emp_code'=>$data[$key]['emp_code'],
                // 'emp_pin'=>Hash::make($data[$key]['emp_pin']),

            ]);
        }
    }
}
