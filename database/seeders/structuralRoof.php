<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StructuralRoofs;

class structuralRoof extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        StructuralRoofs::insert([
            ['name'=>'Tiles'],
            ['name'=>'G.I. Sheet'],
            ['name'=>'Aluminum'],
            ['name'=>'Asbestos'],
            ['name'=>'Long Span'],
            ['name'=>'Concrete Desk'],
            ['name'=>'Nipa/Anahaw/Cogon']
        ]);
    }
}
