<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class BusinessFeesSeeder extends Seeder
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
                'id' => '980b1076-c365-4d66-8cbc-2a4bf31ef013',
                'refID' => 'BUS-FEES'.'-'.str_pad((0), 4, "0", STR_PAD_LEFT),
                'category_id' => 'f1edaf33-ed00-4ac1-ae15-9414705968f5',
                'name' => 'Business Tax',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '980b1089-8a4d-49cc-bf94-1cfc0f0632fd',
                'refID' => 'BUS-FEES'.'-'.str_pad((1), 4, "0", STR_PAD_LEFT),
                'category_id' => '7310293b-27f2-4aed-b774-2dd6675326ee',
                'name' => 'Mayor\'s Permit Fee',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '980b10d5-ff42-46ed-9f8a-6a17e544d053',
                'refID' => 'BUS-FEES'.'-'.str_pad((2), 4, "0", STR_PAD_LEFT),
                'category_id' => 'd8449df4-116c-4327-ba1a-9bde93099990',
                'name' => 'Occupational Permit',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '980b1156-1914-4258-9b07-bc2d85560fc0',
                'refID' => 'BUS-FEES'.'-'.str_pad((3), 4, "0", STR_PAD_LEFT),
                'category_id' => '92beb34e-c7ea-461f-af1e-ee557787dd10',
                'name' => 'Sanitary Permit',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '980b11bb-2fe2-407f-b52f-24723c59649d',
                'refID' => 'BUS-FEES'.'-'.str_pad((4), 4, "0", STR_PAD_LEFT),
                'category_id' => '92beb34e-c7ea-461f-af1e-ee557787dd10',
                'name' => 'Health Certificate',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '980b11dc-67f1-4824-b8bf-a52b0848576a',
                'refID' => 'BUS-FEES'.'-'.str_pad((5), 4, "0", STR_PAD_LEFT),
                'category_id' => '92beb34e-c7ea-461f-af1e-ee557787dd10',
                'name' => 'Garbage Fee',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '980b125b-2b83-4ad9-bf0e-48bd4afe4290',
                'refID' => 'BUS-FEES'.'-'.str_pad((6), 4, "0", STR_PAD_LEFT),
                'category_id' => '7310293b-27f2-4aed-b774-2dd6675326ee',
                'name' => 'Plates / Stickers',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '980b1274-e725-42a8-8e2e-b2505b9b2737',
                'refID' => 'BUS-FEES'.'-'.str_pad((7), 4, "0", STR_PAD_LEFT),
                'category_id' => 'b9e41698-a11c-4728-9429-75f8dae74804',
                'name' => 'Weight & Measure',
                'created_at' => Carbon::now(),
            ],
        ];

        DB::table('business_fees')->insert($items);

    }


}
