<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class FaasMachineryClassificationSeeder extends Seeder
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
                'id' => '54ce5aa0-0da3-41cd-8e0e-cb881803cfc3',
                'refID' => 'MACHINE-CLASS'.'-'.str_pad((0), 4, "0", STR_PAD_LEFT),
                'name' => 'Residential',
                'code' => 'RES',
                'assessmentLevel' => '10%',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 'd35ba252-74a8-4570-b2f9-51f4955a0951',
                'refID' => 'MACHINE-CLASS'.'-'.str_pad((1), 4, "0", STR_PAD_LEFT),
                'name' => 'Commercial',
                'code' => 'COM',
                'assessmentLevel' => '80%',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '30e28cfc-77a0-4939-8415-6bf83796d1ec',
                'refID' => 'MACHINE-CLASS'.'-'.str_pad((2), 4, "0", STR_PAD_LEFT),
                'name' => 'Agricultural',
                'code' => 'AGR',
                'assessmentLevel' => '40%',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 'caa8de93-17ee-4423-b3c9-89cebab513a7',
                'refID' => 'MACHINE-CLASS'.'-'.str_pad((3), 4, "0", STR_PAD_LEFT),
                'name' => 'Industrial',
                'code' => 'IND',
                'assessmentLevel' => '50%',
                'created_at' => Carbon::now(),
            ],
        ];

        DB::table('faas_machinery_classifications')->insert($classifications);

    }


}
