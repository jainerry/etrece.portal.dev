<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class ChartOfAccountLVL1Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $accounts = [
            [
                'id' => '98045273-c28f-4ba0-9b15-7c6ba8c32c20',
                'refID' => 'CHART-ACC-LVL1'.'-'.str_pad((0), 4, "0", STR_PAD_LEFT),
                'name' => 'Assets',
                'code' => '1',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '98045291-a025-47ce-ab13-cc593e19850d',
                'refID' => 'CHART-ACC-LVL1'.'-'.str_pad((1), 4, "0", STR_PAD_LEFT),
                'name' => 'Liabilities',
                'code' => '2',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '980452ae-0855-4232-a63c-d5fa420ed155',
                'refID' => 'CHART-ACC-LVL1'.'-'.str_pad((2), 4, "0", STR_PAD_LEFT),
                'name' => 'Equity',
                'code' => '3',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '980452b7-8f7d-44ce-934a-4996e1ffeef2',
                'refID' => 'CHART-ACC-LVL1'.'-'.str_pad((3), 4, "0", STR_PAD_LEFT),
                'name' => 'Income',
                'code' => '4',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '980452c8-d5d9-4f5e-b319-fbcb5cfadffb',
                'refID' => 'CHART-ACC-LVL1'.'-'.str_pad((4), 4, "0", STR_PAD_LEFT),
                'name' => 'Expenses',
                'code' => '5',
                'created_at' => Carbon::now(),
            ],
        ];

        DB::table('chart_of_accounts_lvl1')->insert($accounts);

    }


}
