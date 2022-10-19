<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $departments = [
            'Assessor Office',
            'BPL Department',
            'CTC Department',
            'MIS Department',
            'RPT Department',
            'Treasury Department',
        ];
        $inputs = [];
        
        foreach($departments as $index =>$department){
            array_push($inputs,[
                'name'=>$department,
                'created_at'=>Carbon::now()
            ]);
        }

        DB::table('departments')->insert($inputs);

    }


}
