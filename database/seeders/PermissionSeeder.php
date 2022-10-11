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
                'name' => 'View users',
                'guard_name' => 'web',
            ],
            [
                'name' => 'Create users',
                'guard_name' => 'web',
            ],
            [
                'name' => 'Edit users',
                'guard_name' => 'web',
            ],
            [
                'name' => 'Delete users',
                'guard_name' => 'web',
            ],
            [
                'name' => 'View roles',
                'guard_name' => 'web',
            ],
            [
                'name' => 'Create roles',
                'guard_name' => 'web',
            ],
            [
                'name' => 'Edit roles',
                'guard_name' => 'web',
            ],
            [
                'name' => 'Delete roles',
                'guard_name' => 'web',
            ],
            [
                'name' => 'View permissions',
                'guard_name' => 'web',
            ],
            [
                'name' => 'Create permissions',
                'guard_name' => 'web',
            ],
            [
                'name' => 'Edit permissions',
                'guard_name' => 'web',
            ],
            [
                'name' => 'Delete permissions',
                'guard_name' => 'web',
            ]
        ]);
    }
}
