<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StructuralRoofs;
use Illuminate\Support\Str;

class structuralRoof extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StructuralRoofs::insert([
            ['id' => STR::uuid(),'refID' => 'STRUC-ROOF-'.str_pad((0), 4, "0", STR_PAD_LEFT),'name'=>'Tiles'],
            ['id' => STR::uuid(),'refID' => 'STRUC-ROOF-'.str_pad((1), 4, "0", STR_PAD_LEFT),'name'=>'G.I. Sheet'],
            ['id' => STR::uuid(),'refID' => 'STRUC-ROOF-'.str_pad((2), 4, "0", STR_PAD_LEFT),'name'=>'Aluminum'],
            ['id' => STR::uuid(),'refID' => 'STRUC-ROOF-'.str_pad((3), 4, "0", STR_PAD_LEFT),'name'=>'Asbestos'],
            ['id' => STR::uuid(),'refID' => 'STRUC-ROOF-'.str_pad((4), 4, "0", STR_PAD_LEFT),'name'=>'Long Span'],
            ['id' => STR::uuid(),'refID' => 'STRUC-ROOF-'.str_pad((5), 4, "0", STR_PAD_LEFT),'name'=>'Concrete Desk'],
            ['id' => STR::uuid(),'refID' => 'STRUC-ROOF-'.str_pad((6), 4, "0", STR_PAD_LEFT),'name'=>'Nipa/Anahaw/Cogon'],
            ['id' => STR::uuid(),'refID' => 'STRUC-ROOF-'.str_pad((7), 4, "0", STR_PAD_LEFT),'name'=>'Others']
        ]);
    }
}
