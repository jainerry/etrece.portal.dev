<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            [
                'name' => 'Super Admin',
                'guard_name' => 'backpack',
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'Module Admin',
                'guard_name' => 'backpack',
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'Normal User',
                'guard_name' => 'backpack',
                'created_at' => Carbon::now(),
            ]
        ]);
    }
}
