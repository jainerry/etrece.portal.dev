<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class FaasMachinerySecondaryOwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faasMachinerySecondaryOwners = [
            [
                'citizen_profile_id' => '55e25338-a90b-466d-b942-4121caab9687',
                'machinery_profile_id' => '98079b17-58e5-4479-816b-ae988ed2b82a',
                'created_at' => Carbon::now(),
            ],
            [
                'citizen_profile_id' => '55e25338-a90b-466d-b942-4121caab9687',
                'machinery_profile_id' => '98079bcc-7b12-42b2-99ee-8a6a0c6dea7d',
                'created_at' => Carbon::now(),
            ],
            [
                'citizen_profile_id' => '4fa638ad-0ae0-400d-ac9c-5c7af12f4949',
                'machinery_profile_id' => '98079bcc-7b12-42b2-99ee-8a6a0c6dea7d',
                'created_at' => Carbon::now(),
            ],
        ];

        DB::table('faas_machinery_secondary_owners')->insert($faasMachinerySecondaryOwners);

    }
}
