<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CitizenProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        DB::table('citizen_profiles')->insert([
            [
                'refId' => 'CID'.Date('mdY').'-'.str_pad((0), 4, "0", STR_PAD_LEFT),
                'fName' => 'John Carlo',
                'mName' => 'Sacro',
                'lName' => 'Salazar',
                'Sex' => '0',
                'bdate' => Carbon::now(),
                'civilStatus'=>'Single',
                'brgyID'=>'0',
                'purokID'=>'0',
                'address'=>'N/A',
                'placeOfOrigin'=>'N/A',
                'created_at' => Carbon::now(),
            ]
        ]);

    }
}
