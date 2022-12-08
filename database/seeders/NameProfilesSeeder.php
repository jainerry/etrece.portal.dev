<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Faker\Generator as Faker;
use App\Models\NameProfiles as np;
use Illuminate\Support\Str;

class NameProfilesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $municipalityIds = array(
            'e947cf0c-8443-4f8d-9d31-f0ab0d35373d',
            '3c2fd9c7-b4ca-44bf-80e2-a17a961b1a46',
            '40e8c2dd-4fcd-4bb7-9765-0e3e01fe4693',
            '29f6dbe1-3687-4ea1-9ca3-fd978c9f0ac8',
            'c9887497-562b-44d4-82a4-6346372b5c07',
            'f562dfce-dacf-4d3c-b2d7-ddf7eb07133b',
            '87218911-a8b1-434a-9778-b914b1842fe0',
            '62e7a9f4-4510-4169-8afa-ff04a6f9183d',
            'afa232c0-41e1-4b02-913c-8233b5743699',
            '645119f8-9787-4499-a459-f2127096d244',
            'ee0640d5-009e-4653-95ab-4809ea0cd809',
            'f4139211-03f8-4b06-bf0d-243a4d12d4af',
            '0b4f9b7a-b2ff-4f48-966e-df35e6c4758f',
            '1fb0b570-4f98-4fa8-8bae-2f54df75f878',
            '86b6ffd3-9984-46f1-9b91-e9c32628eff6',
            'f465fc9c-3b9a-46e1-a309-0efbbf72a0f5',
            'f08bbb25-135c-4670-90ea-e0c1b870fa7f',
            '7c90bc02-c180-4fe3-af45-8ae968f1cb33',
            '34640991-7b6f-4de0-8171-99802ba5764c',
            '3623ff4f-d08a-4df5-9ae4-d21fa978d6b2',
            '25e252af-5f7a-4bec-982c-5ba6ad0cff12',
            '64504def-14c6-415a-9cc7-9f8cad9c5860'
        );

        $nameProfiles = [
            [
                'id'    =>Str::uuid(),
                'refId' => 'BUS-NAME-'.str_pad((0), 4, "0", STR_PAD_LEFT),
                'first_name' => 'Juan',
                'middle_name' => 'Reyes',
                'last_name' => 'Dela Cruz',
                'suffix' => 'SR',
                'sex' => '0',
                'bdate' => '1990-12-07',
                'municipality_id'=>$municipalityIds[0],
                'address'=>'N/A',
                'created_at' => Carbon::now()
            ],
            [
                'id'    =>Str::uuid(),
                'refId' => 'BUS-NAME-'.str_pad((1), 4, "0", STR_PAD_LEFT),
                'first_name' => 'Kelvin',
                'middle_name' => 'Louis',
                'last_name' => 'Doe',
                'suffix' => 'JR',
                'sex' => '0',
                'bdate' => '1991-09-24',
                'municipality_id'=>$municipalityIds[1],
                'address'=>'N/A',
                'created_at' => Carbon::now()
            ]
        ];

        $npid = 2;
        for($i=0; 5>=$i; $i++ ){
            $faker =app(Faker::class);

            $randomNum = rand(0, count($municipalityIds)-1);

            array_push($nameProfiles, [
                'id'    =>Str::uuid(),
                'refId' => 'BUS-NAME-'.str_pad(($npid++), 4, "0", STR_PAD_LEFT),
                'first_name' => $faker->firstNameMale(),
                'middle_name' => '',
                'last_name' => $faker->lastName(),
                'suffix' => '',
                'sex' => '0',
                'bdate' => '1989-11-03',
                'municipality_id'=>$municipalityIds[$randomNum],
                'address'=>'N/A',
                'created_at' => Carbon::now()
            ]);
        }

        np::insert($nameProfiles);

    }
}
