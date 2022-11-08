<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class FaasLandClassificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $classifications = [
            [
                'name' => 'Residential',
                'marketValuePercentage' => '20%',
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'Commercial',
                'marketValuePercentage' => '50%',
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'Agricultural',
                'marketValuePercentage' => '40%',
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'Industrial',
                'marketValuePercentage' => '50%',
                'created_at' => Carbon::now(),
            ],
        ];

        DB::table('faas_land_classifications')->insert($classifications);

    }


}
