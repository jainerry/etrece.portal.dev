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
                'id' => Str::uuid(),
                'refID' => 'MACHINE-CLASS'.'-'.str_pad((0), 4, "0", STR_PAD_LEFT),
                'name' => 'Residential',
                'code' => 'RES',
                'assessmentLevel' => '20%',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => Str::uuid(),
                'refID' => 'MACHINE-CLASS'.'-'.str_pad((1), 4, "0", STR_PAD_LEFT),
                'name' => 'Commercial',
                'code' => 'COM',
                'assessmentLevel' => '50%',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => Str::uuid(),
                'refID' => 'MACHINE-CLASS'.'-'.str_pad((2), 4, "0", STR_PAD_LEFT),
                'name' => 'Agricultural',
                'code' => 'AGR',
                'assessmentLevel' => '40%',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => Str::uuid(),
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
