<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StructuralWalling as sw;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
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
            ['id' => STR::uuid(),'refID' => 'STRUC-WALLING-'.str_pad((0), 4, "0", STR_PAD_LEFT),'name'=>'Reinforced Concrete','created_at'=>Carbon::now()],
            ['id' => STR::uuid(),'refID' => 'STRUC-WALLING-'.str_pad((1), 4, "0", STR_PAD_LEFT),'name'=>'Plain Cement','created_at'=>Carbon::now()],
            ['id' => STR::uuid(),'refID' => 'STRUC-WALLING-'.str_pad((2), 4, "0", STR_PAD_LEFT),'name'=>'Wood','created_at'=>Carbon::now()],
            ['id' => STR::uuid(),'refID' => 'STRUC-WALLING-'.str_pad((3), 4, "0", STR_PAD_LEFT),'name'=>'CHB','created_at'=>Carbon::now()],
            ['id' => STR::uuid(),'refID' => 'STRUC-WALLING-'.str_pad((4), 4, "0", STR_PAD_LEFT),'name'=>'G.I. Sheet','created_at'=>Carbon::now()],
            ['id' => STR::uuid(),'refID' => 'STRUC-WALLING-'.str_pad((5), 4, "0", STR_PAD_LEFT),'name'=>'Build-a-wall','created_at'=>Carbon::now()],
            ['id' => STR::uuid(),'refID' => 'STRUC-WALLING-'.str_pad((6), 4, "0", STR_PAD_LEFT),'name'=>'Sawali','created_at'=>Carbon::now()],
            ['id' => STR::uuid(),'refID' => 'STRUC-WALLING-'.str_pad((7), 4, "0", STR_PAD_LEFT),'name'=>'Bamboo','created_at'=>Carbon::now()],
            ['id' => STR::uuid(),'refID' => 'STRUC-WALLING-'.str_pad((8), 4, "0", STR_PAD_LEFT),'name'=>'Others','created_at'=>Carbon::now()]
        ]);
    }
}
