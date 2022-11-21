<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StructuralFlooring as sf;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
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
            (for upper floors)','created_at'=>Carbon::now()],
            ['id' => STR::uuid(),'refID' => 'STRUC-FLOORING-'.str_pad((1), 4, "0", STR_PAD_LEFT),'name'=>'Plain Cement','created_at'=>Carbon::now()],
            ['id' => STR::uuid(),'refID' => 'STRUC-FLOORING-'.str_pad((2), 4, "0", STR_PAD_LEFT),'name'=>'Marble','created_at'=>Carbon::now()],
            ['id' => STR::uuid(),'refID' => 'STRUC-FLOORING-'.str_pad((3), 4, "0", STR_PAD_LEFT),'name'=>'Wood','created_at'=>Carbon::now()],
            ['id' => STR::uuid(),'refID' => 'STRUC-FLOORING-'.str_pad((4), 4, "0", STR_PAD_LEFT),'name'=>'Tiles','created_at'=>Carbon::now()],
            ['id' => STR::uuid(),'refID' => 'STRUC-FLOORING-'.str_pad((5), 4, "0", STR_PAD_LEFT),'name'=>'Others','created_at'=>Carbon::now()]
          
        ]);
    }
}
