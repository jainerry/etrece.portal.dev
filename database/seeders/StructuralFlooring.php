<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StructuralFlooring as sf;

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
            ['name'=>'Reinforced Concrete
            (for upper floors)'],
            ['name'=>'Plain Cement'],
            ['name'=>'Marble'],
            ['name'=>'Wood'],
            ['name'=>'Tiles'],
            ['name'=>'Others']
          
        ]);
    }
}
