<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BusinessCategory;
use Illuminate\Support\Str;
class BusinessCategories extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        BusinessCategory::insert([
            [
            "id" => Str::uuid(),
            'name' => 'Business Tax',
            'code' => 'BUSCAT 1'
            ],
            [
                "id" => Str::uuid(),
                'name' => "Mayor's Permit",
                'code' => 'BUSCAT 2'
            ],
            ["id" => Str::uuid(),'name' => "Occupational Tax", 'code' => 'BUSCAT 3'],
            ["id" => Str::uuid(),'name' => "Delivery Truck", 'code' => 'BUSCAT 4'],
            ["id" => Str::uuid(),'name' => "Regulatory", 'code' => 'BUSCAT 5'],
        ]);
    }
}
