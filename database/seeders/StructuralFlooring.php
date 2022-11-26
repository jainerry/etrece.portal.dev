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
            ['id' => 'ac013c6a-6c57-41f3-8be9-07782e0cd4ff','refID' => 'STRUC-FLOORING-'.str_pad((0), 4, "0", STR_PAD_LEFT),'name'=>'Reinforced Concrete
            (for upper floors)','created_at'=>Carbon::now()],
            ['id' => '8afb8b4b-cbaa-4c00-bb40-be34aaa08787','refID' => 'STRUC-FLOORING-'.str_pad((1), 4, "0", STR_PAD_LEFT),'name'=>'Plain Cement','created_at'=>Carbon::now()],
            ['id' => '107a5b75-2750-416c-9687-fa7d818863c9','refID' => 'STRUC-FLOORING-'.str_pad((2), 4, "0", STR_PAD_LEFT),'name'=>'Marble','created_at'=>Carbon::now()],
            ['id' => 'ca67ea97-3115-4cf2-ad76-bb9c4424bf61','refID' => 'STRUC-FLOORING-'.str_pad((3), 4, "0", STR_PAD_LEFT),'name'=>'Wood','created_at'=>Carbon::now()],
            ['id' => 'af30a517-986a-47da-9f8d-83d644eee1e0','refID' => 'STRUC-FLOORING-'.str_pad((4), 4, "0", STR_PAD_LEFT),'name'=>'Tiles','created_at'=>Carbon::now()],
            ['id' => '9f7bcd81-5fbc-4fd9-8cda-ee24cd0b6edb','refID' => 'STRUC-FLOORING-'.str_pad((5), 4, "0", STR_PAD_LEFT),'name'=>'Others','created_at'=>Carbon::now()]
          
        ]);
    }
}
