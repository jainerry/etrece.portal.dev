<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Regions;
use Illuminate\Support\Str;
class regionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $regions = [ 'Region I – Ilocos Region',
                    'Region II – Cagayan Valley',
                    'Region III – Central Luzon',
                    'Region IV‑A – CALABARZON',
                    'Region IV-B',
                    'MIMAROPA Region',
                    'Region V – Bicol Region',
                    'Region VI – Western Visayas',
                    'Region VII – Central Visayas',
                    'Region VIII – Eastern Visayas',
                    'Region IX – Zamboanga Peninsula',
                    'Region X – Northern Mindanao',
                    'Region XI – Davao Region',
                    'Region XII – SOCCSKSARGEN',
                    'Region XIII – Caraga',
                    'NCR – National Capital Region',
                    'CAR – Cordillera Administrative Region',
                    'BARMM – Bangsamoro Autonomous Region in Muslim Mindanao'];

                    foreach($regions as $index=>$req){
                       Regions::create([
                        'id' => STR::uuid(),
                        'refID' => 'REG'.Date('mdY').str_pad(($index), 4, "0", STR_PAD_LEFT),
                        'name' => $req
                       ]);
                    }
    }
}
