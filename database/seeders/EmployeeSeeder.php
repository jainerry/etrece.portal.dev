<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $employees = [
            [
                'id'=>'a0803e52-c98c-4867-80ed-a37b3db1af0d',
                'employeeId' => 'EP'.substr(Date('Y'),(strlen(Date('Y'))-2),2).'-'.str_pad((1), 3, "0", STR_PAD_LEFT),
                'refID' => 'EMPLOYEE'.'-'.str_pad((0), 4, "0", STR_PAD_LEFT),
                'firstName' => 'Johny Boy',
                'lastName' => 'Paiton',
                'birthDate' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
            [
                'id'=>'f1bcfe34-6301-4010-9c5c-a4cbd9b048bd',
                'employeeId' => 'EP'.substr(Date('Y'),(strlen(Date('Y'))-2),2).'-'.str_pad((2), 3, "0", STR_PAD_LEFT),
                'refID' => 'EMPLOYEE'.'-'.str_pad((1), 4, "0", STR_PAD_LEFT),
                'firstName' => 'Melvin',
                'lastName' => 'Quijano',
                'birthDate' => Carbon::now(),
                'created_at' => Carbon::now(),
            ]
        ];
        $eid = 2;
        for($i=0; 10>=$i; $i++ ){
            $eid++;
            $faker =app(Faker::class);
            array_push($employees, [
                'id'=>Str::uuid(),
                'employeeId' => 'EP'.substr(Date('Y'),(strlen(Date('Y'))-2),2).'-'.str_pad(($eid), 3, "0", STR_PAD_LEFT),
                'refID' => 'EMPLOYEE'.'-'.str_pad(($eid), 4, "0", STR_PAD_LEFT),
                'firstName' => $faker->firstNameMale(),
                'lastName' => $faker->lastName(),
                'birthDate' => Carbon::now(),
                'created_at' => Carbon::now(),
            ]);
            
        }

        DB::table('employees')->insert($employees);

    }
}
