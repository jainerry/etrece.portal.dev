<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class BarangaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $barangays = ['Aguado',
                'Cabezas',
                'Cabuco',
                'Conchu',
                'De Ocampo',
                'Gregorio',
                'Inocencio',
                'Lallana',
                'Lapidario',
                'Luciano',
                'Osorio','Perez',
                'San Agustin (Pob.)'];
        $inputs = [];
        
        foreach($barangays as $index =>$brgy){
            array_push($inputs,[
                'refID'=>str_pad(($index+1), 3, "0", STR_PAD_LEFT),
                'name'=>$brgy,
                'created_at'=>Carbon::now()
            ]);
        }


        DB::table('barangays')->insert($inputs);

    }


}
