<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class FaasBuildingSecondaryOwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faasBuildingSecondaryOwners = [
            [
                'citizen_profile_id' => '55e25338-a90b-466d-b942-4121caab9687',
                'building_profile_id' => '980767a1-d3a1-4c6c-8977-10871af0da63',
                'created_at' => Carbon::now(),
            ],
            [
                'citizen_profile_id' => '55e25338-a90b-466d-b942-4121caab9687',
                'building_profile_id' => '980768bb-f2c7-4cae-97f7-401b1dfb97f1',
                'created_at' => Carbon::now(),
            ],
            [
                'citizen_profile_id' => '4fa638ad-0ae0-400d-ac9c-5c7af12f4949',
                'building_profile_id' => '980768bb-f2c7-4cae-97f7-401b1dfb97f1',
                'created_at' => Carbon::now(),
            ],
        ];

        DB::table('faas_building_profile_secondary_owners')->insert($faasBuildingSecondaryOwners);

    }
}
