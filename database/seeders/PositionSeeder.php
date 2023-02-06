<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $positions=[
            [
                'name' => 'Developer',
                'display_name' => 'Developer',
            ],
            [
                'name' => 'Tester',
                'display_name' => 'Tester',
            ],
            [
                'name' => 'QA',
                'display_name' => 'Quality Assurance (QA)',
            ],
            [
                'name' => 'BA',
                'display_name' => 'Business Analyst (BA)',
            ],
            [
                'name' => 'PM',
                'display_name' => ' Project Manager (PM)',
            ],
        ];
        $position=new Position();
        foreach ($positions as $key => $value) {
            $position->create([
                'name' => $positions[$key]['name'],
                'display_name' => $positions[$key]['display_name'],
            ]);
        };
        $user_position=[
            [
                'user_id'=>1,
                'position_id'=>5,
            ],
            [
                'user_id'=>2,
                'position_id'=>5,
            ],
            [
                'user_id'=>3,
                'position_id'=>1,
            ],
            [
                'user_id'=>4,
                'position_id'=>2,
            ],
        ];
        DB::table("user_position")->insert($user_position);
    }
}
