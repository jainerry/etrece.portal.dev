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
            ['id' => STR::uuid(),'name'=>'Reinforced Concrete
            (for upper floors)'],
            ['id' => STR::uuid(),'name'=>'Plain Cement'],
            ['id' => STR::uuid(),'name'=>'Marble'],
            ['id' => STR::uuid(),'name'=>'Wood'],
            ['id' => STR::uuid(),'name'=>'Tiles'],
            ['id' => STR::uuid(),'name'=>'Others']
          
        ]);
    }
}
