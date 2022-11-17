<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

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
                'id' => STR::uuid(),
                'refID' => 'STREET-'.str_pad(($index), 4, "0", STR_PAD_LEFT),
                'name'=>$street,
                //'barangayId'=>rand(1,9),
                'created_at'=>Carbon::now()
            ]);
        }

        DB::table('streets')->insert($inputs);

    }


}
