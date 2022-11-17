<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

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
                'id' => Str::uuid(),
                'refID' => 'OFFICE'.'-'.str_pad(($index), 4, "0", STR_PAD_LEFT),
                'name'=>$office,
                'officeLocationId'=>'56cf08b2-8444-4fb2-ba9b-4e3fd0779e87',
                'created_at'=>Carbon::now()
            ]);
        }

        DB::table('offices')->insert($inputs);

    }


}
