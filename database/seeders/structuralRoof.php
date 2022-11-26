<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StructuralRoofs;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
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
            ['id' => '45f6d405-a8d9-4f1a-b2dd-5fe6b1e68176','refID' => 'STRUC-ROOF-'.str_pad((0), 4, "0", STR_PAD_LEFT),'name'=>'Tiles','created_at'=>Carbon::now()],
            ['id' => '59a04888-8fae-4b9d-bc6a-3e0ec8bfd8bb','refID' => 'STRUC-ROOF-'.str_pad((1), 4, "0", STR_PAD_LEFT),'name'=>'G.I. Sheet','created_at'=>Carbon::now()],
            ['id' => '9fd1be3b-6200-48d4-9378-5a8673b15d2a','refID' => 'STRUC-ROOF-'.str_pad((2), 4, "0", STR_PAD_LEFT),'name'=>'Aluminum','created_at'=>Carbon::now()],
            ['id' => 'e718b0e2-2a2e-4c0f-871b-67cd5df6a971','refID' => 'STRUC-ROOF-'.str_pad((3), 4, "0", STR_PAD_LEFT),'name'=>'Asbestos','created_at'=>Carbon::now()],
            ['id' => '82f1738b-bbf3-4323-b3f3-80685be9252b','refID' => 'STRUC-ROOF-'.str_pad((4), 4, "0", STR_PAD_LEFT),'name'=>'Long Span','created_at'=>Carbon::now()],
            ['id' => 'cae6ad7e-7ffe-4a36-8e37-29975d707bd1','refID' => 'STRUC-ROOF-'.str_pad((5), 4, "0", STR_PAD_LEFT),'name'=>'Concrete Desk','created_at'=>Carbon::now()],
            ['id' => '9f252642-d0e1-4e2d-a114-0dc14efe2e53','refID' => 'STRUC-ROOF-'.str_pad((6), 4, "0", STR_PAD_LEFT),'name'=>'Nipa/Anahaw/Cogon','created_at'=>Carbon::now()],
            ['id' => '7d066266-3b91-4174-b20b-857e986451fa','refID' => 'STRUC-ROOF-'.str_pad((7), 4, "0", STR_PAD_LEFT),'name'=>'Others','created_at'=>Carbon::now()]
        ]);
    }
}
