<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class OfficeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $offices = [
            'CITY SLAUGHTHERHOUSE',
            'CITY INFORMATION COMMUNICATION AND TECHNOLOGY OFFICE',
            'CITY MAYORS',
            'CITY VICE MAYORS',
            'CMO',
            'CMO/CSU',
            'CMO/SCHOOL GUARD',
            'CMO/TRAFFICE DIVISION',
            'CSO',
            'CTO'
        ];
        $inputs = [];
        
        foreach($offices as $index =>$office){
            array_push($inputs,[
                'name'=>$office,
                'officeLocationId'=>'1',
                'created_at'=>Carbon::now()
            ]);
        }

        DB::table('offices')->insert($inputs);

    }


}
