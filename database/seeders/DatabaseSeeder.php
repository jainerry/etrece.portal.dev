<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Municipality;
use App\Models\Regions;
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
            NameProfilesSeeder::class,
            BarangaySeeder::class,
            OfficeLocationSeeder::class,
            OfficeSeeder::class,
            PositionSeeder::class,
            SectionSeeder::class,
            AppointmentSeeder::class,
            StreetSeeder::class,
            EmployeeSeeder::class,
            ProvinceSeeder::class,
            MunicipalitiesSeeder::class,
            structuralRoof::class,
            FaasLandClassificationSeeder::class,
            StructuralTypes::class,
            StructuralFlooring::class,
            StructuralWalling::class,
            FaasBuildingClassificationSeeder::class,
            FaasMachineryClassificationSeeder::class,
            regionSeeder::class,
            CTCTypesSeeder::class,
            ChartOfAccountLVL1Seeder::class,
            ChartOfAccountLVL2Seeder::class,
            ChartOfAccountLVL3Seeder::class,
            ChartOfAccountLVL4Seeder::class,
        ]);
    }
}
