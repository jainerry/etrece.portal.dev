<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class StreetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $barangayIds = array(
            '04c43c7a-1329-40eb-9420-acd72b13fc0b',
            '0b3586b5-d1af-4b35-be70-f56b934cdf28',
            '0eaaf54b-8ab2-47a9-8590-35fabd240421',
            '1ccac5aa-b266-4e75-a62f-b5240095fe71',
            '1e5c14c8-d0fe-49f2-81a8-99395768290f',
            '3e835e39-ee3c-4515-858f-a28886b36a07',
            '6d12f763-e932-4fce-9194-bf021c878ebf',
            '88886fb3-6d8a-45ea-91c4-ace3b2e6ac88',
            '8fa932a8-3085-4168-8db3-19beb8096101',
            'c66ab4d6-dcf8-446e-b1fb-baa2ba3324c7',
            'ded2a76e-329c-4174-8f20-b5126cb5d553',
            'e367ac8f-efa2-40c3-89f5-4a2953d80f1c',
            'ea7ef3bd-772c-4a3f-80d2-c1ba2c2dcd21',
        );

        $streets = [
            'Adelfa ',
            'B. Diloy',
            'Bougainvilla ',
            'Cadena De Amor',
            'Camia',
            'Carnation',
            'Chrysanthemum ',
            'Dama de Noche',
            'Geranium ',
            'Kingfisher '
        ];
        $inputs = [];
        
        foreach($streets as $index =>$street){

            $randomNum = rand(0, count($barangayIds)-1);

            array_push($inputs,[
                'id' => STR::uuid(),
                'refID' => 'STREET-'.str_pad(($index), 4, "0", STR_PAD_LEFT),
                'name'=>$street,
                'barangay_id'=>$barangayIds[$randomNum],
                'created_at'=>Carbon::now()
            ]);
        }

        DB::table('streets')->insert($inputs);

    }


}
