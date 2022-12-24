<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BusinessVehicles;

class BusVehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        BusinessVehicles::insert([
            ["id"=>"980edebd-bdae-4679-8f2c-295c8449ae48",
            "refID"=>"BUS-VEHICLE-0000",
            "name"=>"Truck",
            "business_fees_id"=>"980b1274-e725-42a8-8e2e-b2505b9b2738",
            "description" =>"N/A"]
        ]);
    }
}
