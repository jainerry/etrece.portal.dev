<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Faker\Generator as Faker;


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
                'employeeId' => 'EP'.substr(Date('Y'),(strlen(Date('Y'))-2),2).'-'.str_pad((1), 3, "0", STR_PAD_LEFT),
                'firstName' => 'Juan',
                'lastName' => 'Dela Cruz',
                'birthDate' => Carbon::now(),
                'officeId'=>'1',
                'sectionId'=>'1',
                'positionId'=>'1',
                'appointmentId'=>'1',
                'civilStatus'=>'Single',
                'citizenShip'=>'Single',
                'sex'=>'Single',
                'IDNo'=>substr(Date('Y'),(strlen(Date('Y'))-2),2).'-'.'P'.str_pad((1), 3, "0", STR_PAD_LEFT),
                'created_at' => Carbon::now(),
            ],
            [
                'employeeId' => 'EP'.substr(Date('Y'),(strlen(Date('Y'))-2),2).'-'.str_pad((2), 3, "0", STR_PAD_LEFT),
                'firstName' => 'Juana',
                'lastName' => 'Dela Cruz',
                'birthDate' => Carbon::now(),
                'officeId'=>'1',
                'sectionId'=>'1',
                'positionId'=>'1',
                'appointmentId'=>'1',
                'civilStatus'=>'Single',
                'citizenShip'=>'Single',
                'sex'=>'Single',
                'IDNo'=>substr(Date('Y'),(strlen(Date('Y'))-2),2).'-'.'P'.str_pad((2), 3, "0", STR_PAD_LEFT),
                'created_at' => Carbon::now(),
            ]
        ];
        $eid = 2;
        for($i=0; 10>=$i; $i++ ){
            $faker =app(Faker::class);
            array_push($employees, [
                'employeeId' => 'EP'.substr(Date('Y'),(strlen(Date('Y'))-2),2).'-'.str_pad(($eid++), 3, "0", STR_PAD_LEFT),
                'firstName' => $faker->firstNameMale(),
                'lastName' => $faker->lastName(),
                'birthDate' => Carbon::now(),
                'officeId'=>'1',
                'sectionId'=>'1',
                'positionId'=>'1',
                'appointmentId'=>'3',
                'civilStatus'=>'Single',
                'citizenShip'=>'Single',
                'sex'=>'Single',
                'IDNo'=>substr(Date('Y'),(strlen(Date('Y'))-2),2).'-'.'J'.str_pad(($eid++), 3, "0", STR_PAD_LEFT),
                'created_at' => Carbon::now(),
            ]);
            
        }

        DB::table('employees')->insert($employees);

    }
}
