<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\KindOfBuilding;
use Illuminate\Support\Str;

class KindOfBuildingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        KindOfBuilding::insert([
            ['id' => STR::uuid(),'refID' => 'KINDOF-BLDG'.'-'.str_pad((0), 4, "0", STR_PAD_LEFT),'name'=>'Residential'],
            ['id' => STR::uuid(),'refID' => 'KINDOF-BLDG'.'-'.str_pad((1), 4, "0", STR_PAD_LEFT),'name'=>'Industrial'],
            ['id' => STR::uuid(),'refID' => 'KINDOF-BLDG'.'-'.str_pad((2), 4, "0", STR_PAD_LEFT),'name'=>'Commercial'],
            ['id' => STR::uuid(),'refID' => 'KINDOF-BLDG'.'-'.str_pad((3), 4, "0", STR_PAD_LEFT),'name'=>'Agricultural']
        ]);
    }
}
