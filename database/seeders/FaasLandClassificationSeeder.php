<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

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
                'id' => '58e0a3b9-b771-49df-9e39-63e2cb9cb106',
                'refID' => 'LAND-CLASS'.'-'.str_pad((0), 4, "0", STR_PAD_LEFT),
                'name' => 'Residential',
                'code' => 'RES',
                'assessmentLevel' => '10%',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '5109e5db-24ea-49d8-aeda-7ab13b3bd0a0',
                'refID' => 'LAND-CLASS'.'-'.str_pad((1), 4, "0", STR_PAD_LEFT),
                'name' => 'Commercial',
                'code' => 'COM',
                'assessmentLevel' => '50%',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '488a0549-5222-4f10-b30b-3796d8916566',
                'refID' => 'LAND-CLASS'.'-'.str_pad((2), 4, "0", STR_PAD_LEFT),
                'name' => 'Agricultural',
                'code' => 'AGR',
                'assessmentLevel' => '40%',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 'ce234931-ec3b-4b7e-bd95-058641fd8487',
                'refID' => 'LAND-CLASS'.'-'.str_pad((3), 4, "0", STR_PAD_LEFT),
                'name' => 'Industrial',
                'code' => 'IND',
                'assessmentLevel' => '50%',
                'created_at' => Carbon::now(),
            ],
        ];

        DB::table('faas_land_classifications')->insert($classifications);

    }


}
