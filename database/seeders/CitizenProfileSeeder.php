<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Faker\Generator as Faker;
use App\Models\CitizenProfile as cp;
use Illuminate\Support\Str;
use App\Models\Barangay;

class CitizenProfileSeeder extends Seeder
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

        $users = [
            [
                'id'    =>Str::uuid(),
                'refId' => 'CITIZEN-'.str_pad((0), 4, "0", STR_PAD_LEFT),
                'fName' => 'John Carlo',
                'mName' => 'Sacro',
                'lName' => 'Salazar',
                'Sex' => '0',
                'bdate' => Carbon::now(),
                'civilStatus'=>'Single',
                'brgyID'=>$barangayIds[0],
                'purokID'=>'0',
                'address'=>'N/A',
                'placeOfOrigin'=>'N/A',
                'created_at' => Carbon::now(),
            ],
            [
                'id'    =>Str::uuid(),
                'refId' => 'CITIZEN-'.str_pad((1), 4, "0", STR_PAD_LEFT),
                'fName' => 'John ',
                'mName' => 'Sacro',
                'lName' => 'Salazar',
                'Sex' => '0',
                'bdate' => Carbon::now(),
                'civilStatus'=>'Single',
                'brgyID'=>$barangayIds[1],
                'purokID'=>'0',
                'address'=>'N/A',
                'placeOfOrigin'=>'N/A',
                'created_at' => Carbon::now(),
            ]
        ];
        $cid = 2;
        for($i=0; 500>=$i; $i++ ){
            $faker =app(Faker::class);

            $randomNum = rand(0, count($barangayIds)-1);

            array_push($users, [
                'id'    =>Str::uuid(),
                'refId' => 'CITIZEN-'.str_pad(($cid++), 4, "0", STR_PAD_LEFT),
                'fName' => $faker->firstNameMale(),
                'mName' => '',
                'lName' => $faker->lastName(),
                'Sex' => '0',
                'bdate' => Carbon::now(),
                'civilStatus'=>'Single',
                'brgyID'=>$barangayIds[$randomNum],
                'purokID'=>'0',
                'address'=>'N/A',
                'placeOfOrigin'=>'N/A',
                'created_at' => Carbon::now(),
            ]);
            
        }

        cp::insert($users);

    }
}
