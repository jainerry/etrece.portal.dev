<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class StreetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $streets = [
            'Adelfa ',
            'B. Diloy',
            'Bougainvilla ',
            'Cadena De Amor',
            'Camia',
            'Carnation',
            'Chrysanthemum ',
            'Dama de Noche',
            'Geranium ',
            'Kingfisher '
        ];
        $inputs = [];
        
        foreach($streets as $index =>$street){
            array_push($inputs,[
                'name'=>$street,
                'barangayId'=>rand(1,9),
                'created_at'=>Carbon::now()
            ]);
        }

        DB::table('streets')->insert($inputs);

    }


}
