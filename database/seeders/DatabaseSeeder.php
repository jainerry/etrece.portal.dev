<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Municipality;
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
            UsersRolesAndPermissionsSeeder::class,
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
            ProvinceSeeder::class,
            MunicipalitiesSeeder::class,
            structuralRoof::class
        ]);
    }
}
