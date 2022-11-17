<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Province;
use Illuminate\Support\Str;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Province::create([
            'id' => 'eb9e8c56-957b-4084-b5ae-904054d2a1b3',
            'refID' => 'PROVINCE'.'-'.str_pad((0), 4, "0", STR_PAD_LEFT),
            'name'=>'Cavite']);
    }
}
