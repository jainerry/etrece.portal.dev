<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StructuralWalling as sw;
class StructuralWalling extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        sw::insert([
            ['name'=>'Reinforced Concrete'],
            ['name'=>'Plain Cement'],
            ['name'=>'Wood'],
            ['name'=>'CHB'],
            ['name'=>'G.I. Sheet'],
            ['name'=>'Build-a-wall'],
            ['name'=>'Sawali'],
            ['name'=>'Bamboo']
        ]);
    }
}
