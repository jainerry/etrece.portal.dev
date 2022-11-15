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
                'name' => 'Residential',
                'code' => 'RES',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Commercial',
                'code' => 'COM',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Agricultural',
                'code' => 'AGR',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Industrial',
                'code' => 'IND',
                'created_at' => Carbon::now(),
            ],
        ];

        DB::table('faas_machinery_classifications')->insert($classifications);

    }


}
