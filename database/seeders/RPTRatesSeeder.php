<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class RPTRatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $rates = [
            [
                'id' => '980935f0-66d2-44b9-a3b2-360288a5d048',
                'refID' => 'RPT-RATE'.'-'.str_pad((0), 4, "0", STR_PAD_LEFT),
                'name' => 'Basic',
                'percentage' => '1.5%',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '9809360c-1e78-40a8-b989-8b5979aecdd4',
                'refID' => 'RPT-RATE'.'-'.str_pad((1), 4, "0", STR_PAD_LEFT),
                'name' => 'SEF',
                'percentage' => '1%',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '9809361c-9bbf-4530-b811-7b79fc89a4d7',
                'refID' => 'RPT-RATE'.'-'.str_pad((2), 4, "0", STR_PAD_LEFT),
                'name' => 'Penalty',
                'percentage' => '2%',
                'created_at' => Carbon::now(),
            ],
        ];

        DB::table('rpt_rates')->insert($rates);

    }


}
