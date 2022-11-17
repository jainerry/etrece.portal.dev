<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Municipality;
use Illuminate\Support\Str;

class MunicipalitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Municipality::create([
            'id'=>'db3510e6-3add-4d81-8809-effafbbaa6fd',
            'refID' => 'MUNICIPALITY'.'-'.str_pad((0), 4, "0", STR_PAD_LEFT),
            'name'=>'Trece Martires City',
            'province_id' => 'eb9e8c56-957b-4084-b5ae-904054d2a1b3'
        ]);
    }
}
