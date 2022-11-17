<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $sections = [
            'ACCOUNTING DIVISION',
            'MOTORPOOL/AUTOMOTIVE EQUIPMENT OPERATION DIVISION',
            'PERMIT AND LICENSE DIVISION',
            'PERSON WITH DISABILITY AFFAIRS',
            'PLANNING, DESIGNING & PROGRAMMING STAFF',
            'PUBLIC AFFAIRS INFORMATION',
            'REAL PROPERTY TAX DIVISION',
            'SPECIAL SERVICES DIVISION AND NUTRITION SERVICES',
            'TRAFFIC MANAGEMENT DIVISION',
            'TRECE MARTIRES MEMORIAL PARK SECTION',
        ];
        $inputs = [];
        
        foreach($sections as $index =>$section){
            array_push($inputs,[
                'id' => STR::uuid(),
                'refID' => 'SECTION-'.str_pad(($index), 4, "0", STR_PAD_LEFT),
                'name'=>$section,
                //'officeId'=>rand(1,9),
                'created_at'=>Carbon::now()
            ]);
        }

        DB::table('sections')->insert($inputs);

    }


}
