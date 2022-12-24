<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BusinessCategory;
use Illuminate\Support\Str;
class BusCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        BusinessCategory::insert([
            ['id'=>"e223e31d-984a-4147-a6bc-ade3a5a61984","refID"=>"BUS-CAT-0000",'code'=>'a1',"name"=>"BUS CATEGORY 1","description"=>"THIS IS A CATEGORY"],
            ['id'=>Str::uuid(),"refID"=>"BUS-CAT-0001",'code'=>'a2',"name"=>"BUS CATEGORY 2","description"=>"THIS IS A CATEGORY"]
        ]);
    }
}
