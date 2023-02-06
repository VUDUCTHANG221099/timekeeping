<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            RoleSeeder::class,
            ProjectSeeder::class,
            PositionSeeder::class,
            TabletUserSeeder::class,
        ]);
        // \App\Models\User::factory(10)->create();
    }
}
