<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

class OfficeLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('office_locations')->insert([
            [
                'id' => '56cf08b2-8444-4fb2-ba9b-4e3fd0779e87',
                'refID' => 'OFFICE-LOC'.'-'.str_pad((0), 4, "0", STR_PAD_LEFT),
                'name' => 'Main Building',
                'created_at' => Carbon::now(),
            ]
        ]);

    }
}
