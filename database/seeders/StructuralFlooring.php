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
            [
            'id'=>Str::uuid(),  
            'name'=>'Reinforced Concrete
            (for upper floors)'],
            [
                'id'=>Str::uuid(),  
                'name'=>'Plain Cement'],
            ['id'=>Str::uuid(),'name'=>'Marble'],
            ['id'=>Str::uuid(),  'name'=>'Wood'],
            ['id'=>Str::uuid(),  'name'=>'Tiles'],
            ['id'=>Str::uuid(),  'name'=>'Others']
          
        ]);
    }
}
