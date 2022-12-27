<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BusinessFeesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('business_fees')->delete();
        
        \DB::table('business_fees')->insert(array (
            0 => 
            array (
                'category' => '01',
                'created_at' => '2022-12-28 01:01:27',
                'id' => '9815052f-16a0-4ae5-8efa-90aa40973cb0',
                'isActive' => 'Y',
                'name' => 'Business Tax',
                'refID' => 'BUS-FEES-0000',
                'updated_at' => '2022-12-28 01:01:27',
            ),
            1 => 
            array (
                'category' => '02',
                'created_at' => '2022-12-28 01:03:09',
                'id' => '981505cb-d024-4234-aa63-169d0a62960a',
                'isActive' => 'Y',
                'name' => 'Mayors Permit',
                'refID' => 'BUS-FEES-0001',
                'updated_at' => '2022-12-28 01:03:09',
            ),
            2 => 
            array (
                'category' => '03',
                'created_at' => '2022-12-28 01:03:21',
                'id' => '981505de-02f9-46df-9579-b60471bc50b6',
                'isActive' => 'Y',
                'name' => 'Occupational Tax',
                'refID' => 'BUS-FEES-0002',
                'updated_at' => '2022-12-28 01:03:21',
            ),
            3 => 
            array (
                'category' => '05',
                'created_at' => '2022-12-28 01:03:28',
                'id' => '981505e8-fd40-407e-a728-5150d39c77a5',
                'isActive' => 'Y',
                'name' => 'Sanitary Permit',
                'refID' => 'BUS-FEES-0003',
                'updated_at' => '2022-12-28 01:03:35',
            ),
            4 => 
            array (
                'category' => '05',
                'created_at' => '2022-12-28 01:03:45',
                'id' => '98150602-2d7a-477f-ba1d-556c3149cec7',
                'isActive' => 'Y',
                'name' => 'Health Certificate',
                'refID' => 'BUS-FEES-0004',
                'updated_at' => '2022-12-28 01:03:45',
            ),
            5 => 
            array (
                'category' => '05',
                'created_at' => '2022-12-28 01:03:56',
                'id' => '98150613-40b3-4f3e-89f0-6ce941111f47',
                'isActive' => 'Y',
                'name' => 'Garbage Fee',
                'refID' => 'BUS-FEES-0005',
                'updated_at' => '2022-12-28 01:03:56',
            ),
            6 => 
            array (
                'category' => '05',
                'created_at' => '2022-12-28 01:04:07',
                'id' => '98150624-564f-4f2e-b640-f95866497ee7',
                'isActive' => 'Y',
                'name' => 'Plates / Stickers',
                'refID' => 'BUS-FEES-0006',
                'updated_at' => '2022-12-28 01:04:07',
            ),
            7 => 
            array (
                'category' => '05',
                'created_at' => '2022-12-28 01:04:20',
                'id' => '98150637-181f-48e2-ad21-f830aa6123f5',
                'isActive' => 'Y',
                'name' => 'Weight & Measure',
                'refID' => 'BUS-FEES-0007',
                'updated_at' => '2022-12-28 01:04:20',
            ),
            8 => 
            array (
                'category' => '04',
                'created_at' => '2022-12-28 01:04:28',
                'id' => '98150643-c44b-4cf1-b634-b502bf646c85',
                'isActive' => 'Y',
                'name' => 'Delivery Truck',
                'refID' => 'BUS-FEES-0008',
                'updated_at' => '2022-12-28 01:04:28',
            ),
        ));
        
        
    }
}