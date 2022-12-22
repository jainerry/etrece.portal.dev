<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class BusinessActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $items = [
            [
                'id' => '980ae411-d83b-4379-935a-e70898172055',
                'refID' => 'BUS-ACTIVITY'.'-'.str_pad((0), 4, "0", STR_PAD_LEFT),
                'name' => 'Operating',
                'description' => 'Operating activities refer to the core activities performed by an entity daily like production, sales, and marketing.',
                'open' => '0',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '980ae42f-a207-4f0c-bdff-64ee8038cac9',
                'refID' => 'BUS-ACTIVITY'.'-'.str_pad((1), 4, "0", STR_PAD_LEFT),
                'name' => 'Investing',
                'description' => 'Investing activities originate when an entity engages in tasks like purchasing and selling property, plant, and equipment.',
                'open' => '0',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '980ae453-de4e-4222-9922-34a8fd37413f',
                'refID' => 'BUS-ACTIVITY'.'-'.str_pad((2), 4, "0", STR_PAD_LEFT),
                'name' => 'Financing',
                'description' => 'Financing activities are associated with collecting funds for a firm’s growth and attaining financial strength.',
                'open' => '0',
                'created_at' => Carbon::now(),
            ],
        ];

        DB::table('business_activities')->insert($items);

    }


}
