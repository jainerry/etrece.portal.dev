<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StructuralWalling as sw;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
class StructuralWalling extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        sw::insert([
            ['id' => '61804b4b-a76d-4537-bcf9-4a2da54820d1','refID' => 'STRUC-WALLING-'.str_pad((0), 4, "0", STR_PAD_LEFT),'name'=>'Reinforced Concrete','created_at'=>Carbon::now()],
            ['id' => '4d3b65ab-8a24-469d-ae30-2a63638b4161','refID' => 'STRUC-WALLING-'.str_pad((1), 4, "0", STR_PAD_LEFT),'name'=>'Plain Cement','created_at'=>Carbon::now()],
            ['id' => '15c45328-d77a-46f8-b5b1-55aea9e80d29','refID' => 'STRUC-WALLING-'.str_pad((2), 4, "0", STR_PAD_LEFT),'name'=>'Wood','created_at'=>Carbon::now()],
            ['id' => '0463ccf9-cf0f-4914-928a-97824a5db08c','refID' => 'STRUC-WALLING-'.str_pad((3), 4, "0", STR_PAD_LEFT),'name'=>'CHB','created_at'=>Carbon::now()],
            ['id' => 'e7a91b3e-00f5-4baa-9302-76c12fea170e','refID' => 'STRUC-WALLING-'.str_pad((4), 4, "0", STR_PAD_LEFT),'name'=>'G.I. Sheet','created_at'=>Carbon::now()],
            ['id' => '9202242b-ce92-4afb-b0fe-984bfc810e99','refID' => 'STRUC-WALLING-'.str_pad((5), 4, "0", STR_PAD_LEFT),'name'=>'Build-a-wall','created_at'=>Carbon::now()],
            ['id' => 'cca9d87a-97e6-4020-b045-16071e74d136','refID' => 'STRUC-WALLING-'.str_pad((6), 4, "0", STR_PAD_LEFT),'name'=>'Sawali','created_at'=>Carbon::now()],
            ['id' => 'bc4a6597-c2ed-4028-9e18-bd3c3f058132','refID' => 'STRUC-WALLING-'.str_pad((7), 4, "0", STR_PAD_LEFT),'name'=>'Bamboo','created_at'=>Carbon::now()],
            ['id' => '629237bb-c562-43ac-a94a-414dea6e2bcc','refID' => 'STRUC-WALLING-'.str_pad((8), 4, "0", STR_PAD_LEFT),'name'=>'Others','created_at'=>Carbon::now()]
        ]);
    }
}
