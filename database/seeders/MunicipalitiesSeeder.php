<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Municipality;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class MunicipalitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $municipalities = [
            [
                'id'=>'db3510e6-3add-4d81-8809-effafbbaa6fd',
                'refID' => 'MUNICIPALITY'.'-'.str_pad((0), 4, "0", STR_PAD_LEFT),
                'name'=>'Trece Martires City',
                'province_id' => 'eb9e8c56-957b-4084-b5ae-904054d2a1b3'
            ],
            [
                'id'=>'e947cf0c-8443-4f8d-9d31-f0ab0d35373d',
                'refID' => 'MUNICIPALITY'.'-'.str_pad((1), 4, "0", STR_PAD_LEFT),
                'name'=>'Cavite City',
                'province_id' => 'eb9e8c56-957b-4084-b5ae-904054d2a1b3'
            ],
            [
                'id'=>'3c2fd9c7-b4ca-44bf-80e2-a17a961b1a46',
                'refID' => 'MUNICIPALITY'.'-'.str_pad((2), 4, "0", STR_PAD_LEFT),
                'name'=>'Dasmariñas',
                'province_id' => 'eb9e8c56-957b-4084-b5ae-904054d2a1b3'
            ],
            [
                'id'=>'40e8c2dd-4fcd-4bb7-9765-0e3e01fe4693',
                'refID' => 'MUNICIPALITY'.'-'.str_pad((3), 4, "0", STR_PAD_LEFT),
                'name'=>'Bacoor',
                'province_id' => 'eb9e8c56-957b-4084-b5ae-904054d2a1b3'
            ],
            [
                'id'=>'29f6dbe1-3687-4ea1-9ca3-fd978c9f0ac8',
                'refID' => 'MUNICIPALITY'.'-'.str_pad((4), 4, "0", STR_PAD_LEFT),
                'name'=>'Silang',
                'province_id' => 'eb9e8c56-957b-4084-b5ae-904054d2a1b3'
            ],
            [
                'id'=>'c9887497-562b-44d4-82a4-6346372b5c07',
                'refID' => 'MUNICIPALITY'.'-'.str_pad((5), 4, "0", STR_PAD_LEFT),
                'name'=>'General Trias',
                'province_id' => 'eb9e8c56-957b-4084-b5ae-904054d2a1b3'
            ],
            [
                'id'=>'f562dfce-dacf-4d3c-b2d7-ddf7eb07133b',
                'refID' => 'MUNICIPALITY'.'-'.str_pad((6), 4, "0", STR_PAD_LEFT),
                'name'=>'Imus',
                'province_id' => 'eb9e8c56-957b-4084-b5ae-904054d2a1b3'
            ],
            [
                'id'=>'87218911-a8b1-434a-9778-b914b1842fe0',
                'refID' => 'MUNICIPALITY'.'-'.str_pad((7), 4, "0", STR_PAD_LEFT),
                'name'=>'Magallanes',
                'province_id' => 'eb9e8c56-957b-4084-b5ae-904054d2a1b3'
            ],
            [
                'id'=>'62e7a9f4-4510-4169-8afa-ff04a6f9183d',
                'refID' => 'MUNICIPALITY'.'-'.str_pad((8), 4, "0", STR_PAD_LEFT),
                'name'=>'Indang',
                'province_id' => 'eb9e8c56-957b-4084-b5ae-904054d2a1b3'
            ],
            [
                'id'=>'afa232c0-41e1-4b02-913c-8233b5743699',
                'refID' => 'MUNICIPALITY'.'-'.str_pad((9), 4, "0", STR_PAD_LEFT),
                'name'=>'Tanza',
                'province_id' => 'eb9e8c56-957b-4084-b5ae-904054d2a1b3'
            ],
            [
                'id'=>'645119f8-9787-4499-a459-f2127096d244',
                'refID' => 'MUNICIPALITY'.'-'.str_pad((10), 4, "0", STR_PAD_LEFT),
                'name'=>'Tagaytay',
                'province_id' => 'eb9e8c56-957b-4084-b5ae-904054d2a1b3'
            ],
            [
                'id'=>'ee0640d5-009e-4653-95ab-4809ea0cd809',
                'refID' => 'MUNICIPALITY'.'-'.str_pad((11), 4, "0", STR_PAD_LEFT),
                'name'=>'General Emilio Aguinaldo',
                'province_id' => 'eb9e8c56-957b-4084-b5ae-904054d2a1b3'
            ],
            [
                'id'=>'f4139211-03f8-4b06-bf0d-243a4d12d4af',
                'refID' => 'MUNICIPALITY'.'-'.str_pad((12), 4, "0", STR_PAD_LEFT),
                'name'=>'Kawit',
                'province_id' => 'eb9e8c56-957b-4084-b5ae-904054d2a1b3'
            ],
            [
                'id'=>'0b4f9b7a-b2ff-4f48-966e-df35e6c4758f',
                'refID' => 'MUNICIPALITY'.'-'.str_pad((13), 4, "0", STR_PAD_LEFT),
                'name'=>'Amadeo',
                'province_id' => 'eb9e8c56-957b-4084-b5ae-904054d2a1b3'
            ],
            [
                'id'=>'1fb0b570-4f98-4fa8-8bae-2f54df75f878',
                'refID' => 'MUNICIPALITY'.'-'.str_pad((14), 4, "0", STR_PAD_LEFT),
                'name'=>'Naic',
                'province_id' => 'eb9e8c56-957b-4084-b5ae-904054d2a1b3'
            ],
            [
                'id'=>'86b6ffd3-9984-46f1-9b91-e9c32628eff6',
                'refID' => 'MUNICIPALITY'.'-'.str_pad((15), 4, "0", STR_PAD_LEFT),
                'name'=>'Carmona',
                'province_id' => 'eb9e8c56-957b-4084-b5ae-904054d2a1b3'
            ],
            [
                'id'=>'f465fc9c-3b9a-46e1-a309-0efbbf72a0f5',
                'refID' => 'MUNICIPALITY'.'-'.str_pad((16), 4, "0", STR_PAD_LEFT),
                'name'=>'General Mariano Alvarez',
                'province_id' => 'eb9e8c56-957b-4084-b5ae-904054d2a1b3'
            ],
            [
                'id'=>'f08bbb25-135c-4670-90ea-e0c1b870fa7f',
                'refID' => 'MUNICIPALITY'.'-'.str_pad((17), 4, "0", STR_PAD_LEFT),
                'name'=>'Mendez',
                'province_id' => 'eb9e8c56-957b-4084-b5ae-904054d2a1b3'
            ],
            [
                'id'=>'7c90bc02-c180-4fe3-af45-8ae968f1cb33',
                'refID' => 'MUNICIPALITY'.'-'.str_pad((18), 4, "0", STR_PAD_LEFT),
                'name'=>'Alfonso',
                'province_id' => 'eb9e8c56-957b-4084-b5ae-904054d2a1b3'
            ],
            [
                'id'=>'34640991-7b6f-4de0-8171-99802ba5764c',
                'refID' => 'MUNICIPALITY'.'-'.str_pad((19), 4, "0", STR_PAD_LEFT),
                'name'=>'Maragondon',
                'province_id' => 'eb9e8c56-957b-4084-b5ae-904054d2a1b3'
            ],
            [
                'id'=>'3623ff4f-d08a-4df5-9ae4-d21fa978d6b2',
                'refID' => 'MUNICIPALITY'.'-'.str_pad((20), 4, "0", STR_PAD_LEFT),
                'name'=>'Noveleta',
                'province_id' => 'eb9e8c56-957b-4084-b5ae-904054d2a1b3'
            ],
            [
                'id'=>'25e252af-5f7a-4bec-982c-5ba6ad0cff12',
                'refID' => 'MUNICIPALITY'.'-'.str_pad((21), 4, "0", STR_PAD_LEFT),
                'name'=>'Rosario',
                'province_id' => 'eb9e8c56-957b-4084-b5ae-904054d2a1b3'
            ],
            [
                'id'=>'64504def-14c6-415a-9cc7-9f8cad9c5860',
                'refID' => 'MUNICIPALITY'.'-'.str_pad((22), 4, "0", STR_PAD_LEFT),
                'name'=>'Ternate',
                'province_id' => 'eb9e8c56-957b-4084-b5ae-904054d2a1b3'
            ],
        ];

        DB::table('municipalities')->insert($municipalities);
    }
}
