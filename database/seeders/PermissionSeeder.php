<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
            ],
            [
                'name' => 'create-users',
                'guard_name' => 'backpack',
            ],
            [
                'name' => 'edit-users',
                'guard_name' => 'backpack',
            ],
            [
                'name' => 'delete-users',
                'guard_name' => 'backpack',
            ],
            [
                'name' => 'view-roles',
                'guard_name' => 'backpack',
            ],
            [
                'name' => 'create-roles',
                'guard_name' => 'backpack',
            ],
            [
                'name' => 'edit-roles',
                'guard_name' => 'backpack',
            ],
            [
                'name' => 'delete-roles',
                'guard_name' => 'backpack',
            ],
            [
                'name' => 'view-permissions',
                'guard_name' => 'backpack',
            ],
            [
                'name' => 'create-permissions',
                'guard_name' => 'backpack',
            ],
            [
                'name' => 'edit-permissions',
                'guard_name' => 'backpack',
            ],
            [
                'name' => 'delete-permissions',
                'guard_name' => 'backpack',
            ]
        ]);
    }
}
