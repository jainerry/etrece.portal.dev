<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class CTCTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $types = [
            [
                'id' => 'be9ba3b9-e6e8-46fa-828b-b57efd92a83a',
                'refID' => 'CTC-TYPE'.'-'.str_pad((0), 4, "0", STR_PAD_LEFT),
                'name' => 'Individual',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 'ae3f579e-d491-4635-80aa-49172e22cb47',
                'refID' => 'CTC-TYPE'.'-'.str_pad((1), 4, "0", STR_PAD_LEFT),
                'name' => 'Individual - Sole Proprietor',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '4a89ee8c-0aae-426e-83ec-998e25692724',
                'refID' => 'CTC-TYPE'.'-'.str_pad((2), 4, "0", STR_PAD_LEFT),
                'name' => 'Corporation',
                'created_at' => Carbon::now(),
            ],
        ];

        DB::table('ctc_types')->insert($types);

    }


}
