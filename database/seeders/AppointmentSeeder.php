<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $appointments = [
            'Permanent',
            'Co-Termimus',
            'Casual',
            'Job Order',
            'CPAG'
        ];
        $inputs = [];
        
        foreach($appointments as $index => $appointment){
            array_push($inputs,[
                'id' => STR::uuid(),
                'refID' => 'APP-STAT'.'-'.str_pad(($index), 4, "0", STR_PAD_LEFT),
                'name'=>$appointment,
                'created_at'=>Carbon::now()
            ]);
        }

        DB::table('appointments')->insert($inputs);

    }


}
