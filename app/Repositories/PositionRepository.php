<?php

namespace App\Repositories;

use App\Models\Position;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class PositionRepository
{
    public function all()
    {
        return Position::all();
    }
    public function create($namePosition,$display_name){
        $position = new Position();
        $position->create([
            'name' => Str::of($namePosition)->trim(),
            'display_name' => Str::of($display_name)->trim(),
        ]);
    }
    public function getPosition($id)
    {
        return Position::where("id", $id)->first();
    }
    public function update($id, $display_name)
    {
        Position::where('id', $id)->update([
            'display_name' => Str::of($display_name)->trim(),
        ]);
    }
    public function destroy($id){
        Position::where('id', $id)->delete();
    }
    public function createUser_position($user_id, $positions)
    {
        if (is_array($positions)) {

            foreach ($positions as $key => $value) {
                DB::table('user_position')->insert([
                    'user_id' => $user_id,
                    'position_id' => $value,
                ]);
            }
        } else {
            DB::table('user_position')->insert([
                'user_id' => $user_id,
                'position_id' => $positions,
            ]);
        }
    }
    public function updateUserPosition($user_id, $positions)
    {
        if (is_array($positions)) {
            DB::table('user_position')->where('user_id', $user_id)->delete();
            foreach ($positions as $key => $value) {
                DB::table('user_position')->insert([
                    'user_id' => $user_id,
                    'position_id' => $value,
                ]);
            }
        }
        DB::table("user_position")->where("user_id", $user_id)->update([
            "position_id" => $positions,
        ]);
    }
}
