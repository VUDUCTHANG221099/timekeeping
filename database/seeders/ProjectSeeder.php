<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $projects = [
            [
                'name' => "Vaix Daily Report System",
                'start_project' => '2022-09-16',
                'end_project' => null,
                'description' => null,
                'status' => "0",

            ],
            [
                'name' => "Phát hiện gian lận trong thi cử",
                'start_project' => '2022-10-18',
                'end_project' => null,
                'description' => null,
                'status' => "2",


            ],
            [
                'name' => "Hệ thống xe tự lái",
                'start_project' => '2022-10-18',
                'end_project' => null,
                'description' => null,
                'status' => "0",


            ],
            [
                'name' => "Dự án Net Master I",
                'start_project' => '2022-11-11',
                'end_project' => null,
                'description' => null,
                'status' => "0",

            ],
            [
                'name' => "Dự án Net Master II",
                'start_project' => '2022-11-13',
                'end_project' => null,
                'description' => null,
                'status' => "0",


            ],
            [
                'name' => "Dự án SKC I",
                'start_project' => '2022-10-11',
                'end_project' => null,
                'description' => null,
                'status' => "0",


            ],
            [
                'name' => "Dự án SKC V",
                'start_project' => '2022-11-11',
                'end_project' => null,
                'description' => null,
                'status' => "0",


            ],
        ];
        $project = new Project();
        foreach ($projects as $key => $value) {
            $project->create([
                'name' => $projects[$key]['name'],
                // 'start_project' => $projects[$key]['start_project'],
                // 'end_project' => $projects[$key]['end_project'],
                'description' => $projects[$key]['description'],
                'status' => $projects[$key]['status'],
                // 'cost' =>($key++<0?1:$key)."0000000",
                // 'menber' =>$key++."0",


            ]);
        }
    }
}
