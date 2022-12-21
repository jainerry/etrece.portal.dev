<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class FaasLandSecondaryOwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faasLandSecondaryOwners = [
            [
                'citizen_profile_id' => '55e25338-a90b-466d-b942-4121caab9687',
                'land_profile_id' => '98074d71-16bf-4b19-a9ad-d91786682ab1',
                'created_at' => Carbon::now(),
            ],
            [
                'citizen_profile_id' => 'b969444e-9e09-4945-949a-69f1a6278ceb',
                'land_profile_id' => '980760e0-6d4f-467c-b8fb-4ee39466a3ce',
                'created_at' => Carbon::now(),
            ],
            [
                'citizen_profile_id' => '4fa638ad-0ae0-400d-ac9c-5c7af12f4949',
                'land_profile_id' => '9807530e-d573-4d12-b918-c1f70d952a82',
                'created_at' => Carbon::now(),
            ],
            [
                'citizen_profile_id' => '55e25338-a90b-466d-b942-4121caab9687',
                'land_profile_id' => '9807530e-d573-4d12-b918-c1f70d952a82',
                'created_at' => Carbon::now(),
            ],
        ];

        DB::table('faas_land_secondary_owners')->insert($faasLandSecondaryOwners);

    }
}
