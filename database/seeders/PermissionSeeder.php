<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->insert([
            [
                'name' => 'view-users',
                'guard_name' => 'backpack',
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'create-users',
                'guard_name' => 'backpack',
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'edit-users',
                'guard_name' => 'backpack',
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'delete-users',
                'guard_name' => 'backpack',
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'view-roles',
                'guard_name' => 'backpack',
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'create-roles',
                'guard_name' => 'backpack',
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'edit-roles',
                'guard_name' => 'backpack',
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'delete-roles',
                'guard_name' => 'backpack',
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'view-permissions',
                'guard_name' => 'backpack',
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'create-permissions',
                'guard_name' => 'backpack',
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'edit-permissions',
                'guard_name' => 'backpack',
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'delete-permissions',
                'guard_name' => 'backpack',
                'created_at' => Carbon::now(),
            ]
        ]);
    }
}
