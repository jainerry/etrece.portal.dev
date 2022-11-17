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
                'id'=>Str::uuid(),
                'employeeId' => 'EP'.substr(Date('Y'),(strlen(Date('Y'))-2),2).'-'.str_pad((1), 3, "0", STR_PAD_LEFT),
                'refID' => 'EMPLOYEE'.'-'.str_pad((0), 4, "0", STR_PAD_LEFT),
                'firstName' => 'Juan',
                'lastName' => 'Dela Cruz',
                'birthDate' => Carbon::now(),
                // 'officeId'=>'1',
                // 'sectionId'=>'1',
                // 'positionId'=>'1',
                // 'appointmentId'=>'1',
                // 'civilStatus'=>'Single',
                // 'citizenShip'=>'Single',
                // 'sex'=>'Single',
                // 'IDNo'=>substr(Date('Y'),(strlen(Date('Y'))-2),2).'-'.'P'.str_pad((1), 3, "0", STR_PAD_LEFT),
                'created_at' => Carbon::now(),
            ],
            [
                'id'=>Str::uuid(),
                'employeeId' => 'EP'.substr(Date('Y'),(strlen(Date('Y'))-2),2).'-'.str_pad((2), 3, "0", STR_PAD_LEFT),
                'refID' => 'EMPLOYEE'.'-'.str_pad((1), 4, "0", STR_PAD_LEFT),
                'firstName' => 'Juana',
                'lastName' => 'Dela Cruz',
                'birthDate' => Carbon::now(),
                // 'officeId'=>'1',
                // 'sectionId'=>'1',
                // 'positionId'=>'1',
                // 'appointmentId'=>'1',
                // 'civilStatus'=>'Single',
                // 'citizenShip'=>'Single',
                // 'sex'=>'Single',
                // 'IDNo'=>substr(Date('Y'),(strlen(Date('Y'))-2),2).'-'.'P'.str_pad((2), 3, "0", STR_PAD_LEFT),
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
                // 'officeId'=>'1',
                // 'sectionId'=>'1',
                // 'positionId'=>'1',
                // 'appointmentId'=>'3',
                // 'civilStatus'=>'Single',
                // 'citizenShip'=>'Single',
                // 'sex'=>'Single',
                // 'IDNo'=>substr(Date('Y'),(strlen(Date('Y'))-2),2).'-'.'J'.str_pad(($eid++), 3, "0", STR_PAD_LEFT),
                'created_at' => Carbon::now(),
            ]);
            
        }

        DB::table('employees')->insert($employees);

    }
}
