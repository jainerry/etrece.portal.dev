<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;
use Yajra\Address\Entities\Barangay;

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
            PermissionSeeder::class,
            CitizenProfileSeeder::class,
            BarangaySeeder::class,
        ]);
    }
}
