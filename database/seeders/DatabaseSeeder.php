<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

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
            PermissionSeeder::class,
            CitizenProfileSeeder::class,
            BarangaySeeder::class,
            OfficeLocationSeeder::class,
            OfficeSeeder::class,
            PositionSeeder::class,
            SectionSeeder::class,
            AppointmentSeeder::class,
            StreetSeeder::class,
            EmployeeSeeder::class,
            DepartmentSeeder::class,
        ]);
    }
}
