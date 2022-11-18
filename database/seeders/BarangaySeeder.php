<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class BarangaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $barangays = [
            [
                'id' => '04c43c7a-1329-40eb-9420-acd72b13fc0b',
                'refID' => 'BRGY'.'-'.str_pad((0), 4, "0", STR_PAD_LEFT),
                'name' => 'Cabezas',
                'municipality_id'=>'db3510e6-3add-4d81-8809-effafbbaa6fd',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 'c66ab4d6-dcf8-446e-b1fb-baa2ba3324c7',
                'refID' => 'BRGY'.'-'.str_pad((1), 4, "0", STR_PAD_LEFT),
                'name' => 'Cabuco',
                'municipality_id'=>'db3510e6-3add-4d81-8809-effafbbaa6fd',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '1ccac5aa-b266-4e75-a62f-b5240095fe71',
                'refID' => 'BRGY'.'-'.str_pad((2), 4, "0", STR_PAD_LEFT),
                'name' => 'Conchu',
                'municipality_id'=>'db3510e6-3add-4d81-8809-effafbbaa6fd',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '1e5c14c8-d0fe-49f2-81a8-99395768290f',
                'refID' => 'BRGY'.'-'.str_pad((3), 4, "0", STR_PAD_LEFT),
                'name' => 'De Ocampo',
                'municipality_id'=>'db3510e6-3add-4d81-8809-effafbbaa6fd',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 'e367ac8f-efa2-40c3-89f5-4a2953d80f1c',
                'refID' => 'BRGY'.'-'.str_pad((4), 4, "0", STR_PAD_LEFT),
                'name' => 'Gregorio',
                'municipality_id'=>'db3510e6-3add-4d81-8809-effafbbaa6fd',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 'ea7ef3bd-772c-4a3f-80d2-c1ba2c2dcd21',
                'refID' => 'BRGY'.'-'.str_pad((5), 4, "0", STR_PAD_LEFT),
                'name' => 'Inocencio',
                'municipality_id'=>'db3510e6-3add-4d81-8809-effafbbaa6fd',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '3e835e39-ee3c-4515-858f-a28886b36a07',
                'refID' => 'BRGY'.'-'.str_pad((6), 4, "0", STR_PAD_LEFT),
                'name' => 'Lallana',
                'municipality_id'=>'db3510e6-3add-4d81-8809-effafbbaa6fd',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '0eaaf54b-8ab2-47a9-8590-35fabd240421',
                'refID' => 'BRGY'.'-'.str_pad((7), 4, "0", STR_PAD_LEFT),
                'name' => 'Lapidario',
                'municipality_id'=>'db3510e6-3add-4d81-8809-effafbbaa6fd',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '88886fb3-6d8a-45ea-91c4-ace3b2e6ac88',
                'refID' => 'BRGY'.'-'.str_pad((8), 4, "0", STR_PAD_LEFT),
                'name' => 'Luciano',
                'municipality_id'=>'db3510e6-3add-4d81-8809-effafbbaa6fd',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 'ded2a76e-329c-4174-8f20-b5126cb5d553',
                'refID' => 'BRGY'.'-'.str_pad((9), 4, "0", STR_PAD_LEFT),
                'name' => 'Osorio',
                'municipality_id'=>'db3510e6-3add-4d81-8809-effafbbaa6fd',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '6d12f763-e932-4fce-9194-bf021c878ebf',
                'refID' => 'BRGY'.'-'.str_pad((10), 4, "0", STR_PAD_LEFT),
                'name' => 'Perez',
                'municipality_id'=>'db3510e6-3add-4d81-8809-effafbbaa6fd',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '0b3586b5-d1af-4b35-be70-f56b934cdf28',
                'refID' => 'BRGY'.'-'.str_pad((11), 4, "0", STR_PAD_LEFT),
                'name' => 'San Agustin (Pob.)',
                'municipality_id'=>'db3510e6-3add-4d81-8809-effafbbaa6fd',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '8fa932a8-3085-4168-8db3-19beb8096101',
                'refID' => 'BRGY'.'-'.str_pad((12), 4, "0", STR_PAD_LEFT),
                'name' => 'Aguado',
                'municipality_id'=>'db3510e6-3add-4d81-8809-effafbbaa6fd',
                'created_at' => Carbon::now(),
            ],

        ];

        DB::table('barangays')->insert($barangays);

    }


}
