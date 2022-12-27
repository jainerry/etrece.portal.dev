<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class StreetsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('streets')->delete();
        
        \DB::table('streets')->insert(array (
            0 => 
            array (
                'barangay_id' => 'c66ab4d6-dcf8-446e-b1fb-baa2ba3324c7',
                'code' => NULL,
                'created_at' => '2022-12-27 00:00:17',
                'id' => '099e4a87-5b3d-4f64-a413-3a337d4473fe',
                'isActive' => 'Y',
                'name' => 'Bougainvilla ',
                'refID' => 'STREET-0002',
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'barangay_id' => '1e5c14c8-d0fe-49f2-81a8-99395768290f',
                'code' => NULL,
                'created_at' => '2022-12-27 00:00:17',
                'id' => '1f4f66f6-4a74-47d4-981a-ac5c12dca6c2',
                'isActive' => 'Y',
                'name' => 'Adelfa ',
                'refID' => 'STREET-0000',
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'barangay_id' => '04c43c7a-1329-40eb-9420-acd72b13fc0b',
                'code' => NULL,
                'created_at' => '2022-12-27 00:00:17',
                'id' => '2d61ec3b-bb42-4931-bd8d-5e9c088bfa90',
                'isActive' => 'Y',
                'name' => 'Camia',
                'refID' => 'STREET-0004',
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'barangay_id' => 'ded2a76e-329c-4174-8f20-b5126cb5d553',
                'code' => NULL,
                'created_at' => '2022-12-27 00:00:17',
                'id' => '38d36959-43d4-4f6e-b678-bfdbd0355407',
                'isActive' => 'Y',
                'name' => 'Kingfisher ',
                'refID' => 'STREET-0009',
                'updated_at' => NULL,
            ),
            4 => 
            array (
                'barangay_id' => '88886fb3-6d8a-45ea-91c4-ace3b2e6ac88',
                'code' => NULL,
                'created_at' => '2022-12-27 00:00:17',
                'id' => '3f00bcaa-57ee-437a-85a1-37f09cdb44f0',
                'isActive' => 'Y',
                'name' => 'Cadena De Amor',
                'refID' => 'STREET-0003',
                'updated_at' => NULL,
            ),
            5 => 
            array (
                'barangay_id' => '1e5c14c8-d0fe-49f2-81a8-99395768290f',
                'code' => NULL,
                'created_at' => '2022-12-27 00:00:17',
                'id' => '72c9db0a-2097-4bad-8b21-6a2c5a200ae9',
                'isActive' => 'Y',
                'name' => 'Chrysanthemum ',
                'refID' => 'STREET-0006',
                'updated_at' => NULL,
            ),
            6 => 
            array (
                'barangay_id' => 'ded2a76e-329c-4174-8f20-b5126cb5d553',
                'code' => NULL,
                'created_at' => '2022-12-27 00:00:17',
                'id' => '8f34c54c-6ad8-42dd-991a-b9d879992358',
                'isActive' => 'Y',
                'name' => 'B. Diloy',
                'refID' => 'STREET-0001',
                'updated_at' => NULL,
            ),
            7 => 
            array (
                'barangay_id' => '6d12f763-e932-4fce-9194-bf021c878ebf',
                'code' => NULL,
                'created_at' => '2022-12-27 00:00:17',
                'id' => '96873f17-ebfe-4604-9db0-a3409a165723',
                'isActive' => 'Y',
                'name' => 'Dama de Noche',
                'refID' => 'STREET-0007',
                'updated_at' => NULL,
            ),
            8 => 
            array (
                'barangay_id' => '6d12f763-e932-4fce-9194-bf021c878ebf',
                'code' => NULL,
                'created_at' => '2022-12-27 00:00:17',
                'id' => 'c921ac32-66b0-4a3c-88f0-f2cf6227ab14',
                'isActive' => 'Y',
                'name' => 'Geranium ',
                'refID' => 'STREET-0008',
                'updated_at' => NULL,
            ),
            9 => 
            array (
                'barangay_id' => 'ea7ef3bd-772c-4a3f-80d2-c1ba2c2dcd21',
                'code' => NULL,
                'created_at' => '2022-12-27 00:00:17',
                'id' => 'f1afb1fa-6ead-4364-897b-8d130e8e7682',
                'isActive' => 'Y',
                'name' => 'Carnation',
                'refID' => 'STREET-0005',
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}