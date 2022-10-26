<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $positions = [
            'ACCOUNT EXAMINER I',
            'ADMINISTRATIVE AIDE II (BOOKBINDER II)',
            'COMMUNICATION AFFAIRS OFFICER IV',
            'COMMUNICATION EQUIPMENT OPERATOR II',
            'CONSTRUCTION & MAINTENANCE FOREMAN',
            'CONSTRUCTION & MAINTENANCE MAN',
            'DAYCARE WORKER',
            'DENTAL AIDE',
            'DENTIST II',
            'DISABILITY AFFAIRS OFFICER IV'
        ];
        $inputs = [];
        
        foreach($positions as $index =>$position){
            array_push($inputs,[
                'name'=>$position,
                'created_at'=>Carbon::now()
            ]);
        }

        DB::table('positions')->insert($inputs);

    }


}
