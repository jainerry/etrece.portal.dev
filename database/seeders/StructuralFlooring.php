<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StructuralFlooring as sf;
use Illuminate\Support\Str;

class StructuralFlooring extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        sf::insert([
            ['id' => STR::uuid(),'refID' => 'STRUC-FLOORING-'.str_pad((0), 4, "0", STR_PAD_LEFT),'name'=>'Reinforced Concrete
            (for upper floors)'],
            ['id' => STR::uuid(),'refID' => 'STRUC-FLOORING-'.str_pad((1), 4, "0", STR_PAD_LEFT),'name'=>'Plain Cement'],
            ['id' => STR::uuid(),'refID' => 'STRUC-FLOORING-'.str_pad((2), 4, "0", STR_PAD_LEFT),'name'=>'Marble'],
            ['id' => STR::uuid(),'refID' => 'STRUC-FLOORING-'.str_pad((3), 4, "0", STR_PAD_LEFT),'name'=>'Wood'],
            ['id' => STR::uuid(),'refID' => 'STRUC-FLOORING-'.str_pad((4), 4, "0", STR_PAD_LEFT),'name'=>'Tiles'],
            ['id' => STR::uuid(),'refID' => 'STRUC-FLOORING-'.str_pad((5), 4, "0", STR_PAD_LEFT),'name'=>'Others']
          
        ]);
    }
}
