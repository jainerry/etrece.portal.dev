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
        
        foreach($positions as $index =>$position){
            array_push($inputs,[
                'name'=>$position,
                'created_at'=>Carbon::now()
            ]);
        }

        DB::table('positions')->insert($inputs);

    }


}
