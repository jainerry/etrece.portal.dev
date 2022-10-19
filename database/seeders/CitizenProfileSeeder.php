<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Faker\Generator as Faker;


class CitizenProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'refId' => 'CID'.Date('mdY').'-'.str_pad((0), 4, "0", STR_PAD_LEFT),
                'fName' => 'John Carlo',
                'mName' => 'Sacro',
                'lName' => 'Salazar',
                'Sex' => '0',
                'bdate' => Carbon::now(),
                'civilStatus'=>'Single',
                'brgyID'=>'0',
                'purokID'=>'0',
                'address'=>'N/A',
                'placeOfOrigin'=>'N/A',
                'created_at' => Carbon::now(),
            ],
            [
                'refId' => 'CID'.Date('mdY').'-'.str_pad((1), 4, "0", STR_PAD_LEFT),
                'fName' => 'John ',
                'mName' => 'Sacro',
                'lName' => 'Salazar',
                'Sex' => '0',
                'bdate' => Carbon::now(),
                'civilStatus'=>'Single',
                'brgyID'=>'0',
                'purokID'=>'0',
                'address'=>'N/A',
                'placeOfOrigin'=>'N/A',
                'created_at' => Carbon::now(),
            ]
        ];
        $cid = 2;
        for($i=0; 2000>=$i; $i++ ){
            $faker =app(Faker::class);
            array_push($users, [
                'refId' => 'CID'.Date('mdY').'-'.str_pad(($cid++), 4, "0", STR_PAD_LEFT),
                'fName' => $faker->firstNameMale(),
                'mName' => '',
                'lName' => $faker->lastName(),
                'Sex' => '0',
                'bdate' => Carbon::now(),
                'civilStatus'=>'Single',
                'brgyID'=>rand(1,13),
                'purokID'=>'0',
                'address'=>'N/A',
                'placeOfOrigin'=>'N/A',
                'created_at' => Carbon::now(),
            ]);
            
        }

        DB::table('citizen_profiles')->insert($users);

    }
}
