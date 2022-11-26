<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class FaasBuildingClassificationSeeder extends Seeder
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
                'id' => '68b4f189-7cd1-4215-ae3b-b87af9e674c0',
                'refID' => 'BLDG-CLASS'.'-'.str_pad((0), 4, "0", STR_PAD_LEFT),
                'name' => 'Residential',
                'code' => 'RES',
                'assessmentLevel' => '10%',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '93fc3f2f-b970-48a8-892e-8af78d8705c8',
                'refID' => 'BLDG-CLASS'.'-'.str_pad((1), 4, "0", STR_PAD_LEFT),
                'name' => 'Commercial',
                'code' => 'COM',
                'assessmentLevel' => '50%',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '5ebe0808-2e04-4463-a5fa-aefb6a1e52fd',
                'refID' => 'BLDG-CLASS'.'-'.str_pad((2), 4, "0", STR_PAD_LEFT),
                'name' => 'Agricultural',
                'code' => 'AGR',
                'assessmentLevel' => '40%',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '13af2cd2-7387-4f25-87dc-f5bc07ac0363',
                'refID' => 'BLDG-CLASS'.'-'.str_pad((3), 4, "0", STR_PAD_LEFT),
                'name' => 'Industrial',
                'code' => 'IND',
                'assessmentLevel' => '50%',
                'created_at' => Carbon::now(),
            ],
        ];

        DB::table('faas_building_classifications')->insert($classifications);

    }


}
