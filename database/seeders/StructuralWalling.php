<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StructuralWalling as sw;
use Illuminate\Support\Str;

class StructuralWalling extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        sw::insert([
            ['id' => STR::uuid(),'refID' => 'STRUC-WALLING-'.str_pad((0), 4, "0", STR_PAD_LEFT),'name'=>'Reinforced Concrete'],
            ['id' => STR::uuid(),'refID' => 'STRUC-WALLING-'.str_pad((1), 4, "0", STR_PAD_LEFT),'name'=>'Plain Cement'],
            ['id' => STR::uuid(),'refID' => 'STRUC-WALLING-'.str_pad((2), 4, "0", STR_PAD_LEFT),'name'=>'Wood'],
            ['id' => STR::uuid(),'refID' => 'STRUC-WALLING-'.str_pad((3), 4, "0", STR_PAD_LEFT),'name'=>'CHB'],
            ['id' => STR::uuid(),'refID' => 'STRUC-WALLING-'.str_pad((4), 4, "0", STR_PAD_LEFT),'name'=>'G.I. Sheet'],
            ['id' => STR::uuid(),'refID' => 'STRUC-WALLING-'.str_pad((5), 4, "0", STR_PAD_LEFT),'name'=>'Build-a-wall'],
            ['id' => STR::uuid(),'refID' => 'STRUC-WALLING-'.str_pad((6), 4, "0", STR_PAD_LEFT),'name'=>'Sawali'],
            ['id' => STR::uuid(),'refID' => 'STRUC-WALLING-'.str_pad((7), 4, "0", STR_PAD_LEFT),'name'=>'Bamboo'],
            ['id' => STR::uuid(),'refID' => 'STRUC-WALLING-'.str_pad((8), 4, "0", STR_PAD_LEFT),'name'=>'Others']
        ]);
    }
}
