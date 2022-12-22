<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class BusinessJobCategorySeeder extends Seeder
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
                'id' => '01gmx9qfv1ng5hvqprmcs6kg10',
                'refID' => 'JOB-CAT'.'-'.str_pad((0), 4, "0", STR_PAD_LEFT),
                'name' => 'Operational & Technical',
                'description' => '',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '01gmx9qxty2fd66jrw4yk8w4hy',
                'refID' => 'JOB-CAT'.'-'.str_pad((1), 4, "0", STR_PAD_LEFT),
                'name' => 'Professional',
                'description' => '',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '01gmx9r3b4s1eberkmp1nq9ymt',
                'refID' => 'JOB-CAT'.'-'.str_pad((2), 4, "0", STR_PAD_LEFT),
                'name' => 'Supervisory & Managerial',
                'description' => '',
                'created_at' => Carbon::now(),
            ],
        ];

        DB::table('business_job_categories')->insert($items);

    }


}
