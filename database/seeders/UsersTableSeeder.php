<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users')->delete();
        
        \DB::table('users')->insert(array (
            0 => 
            array (
                'created_at' => '2022-12-27 00:00:17',
                'email' => 'superadmin@etreceportal.com',
                'email_verified_at' => NULL,
                'id' => 1,
                'isActive' => 'Y',
                'name' => 'Super Admin',
                'password' => '$2y$10$sDEA1vecSLT2cExr9X1jQO69HpbnLXxKic8d8cz8yGwh1pclvMPGC',
                'remember_token' => NULL,
                'updated_at' => '2022-12-27 00:00:17',
            ),
            1 => 
            array (
                'created_at' => '2022-12-27 00:00:17',
                'email' => 'rptadmin@etreceportal.com',
                'email_verified_at' => NULL,
                'id' => 2,
                'isActive' => 'Y',
                'name' => 'RPT Admin',
                'password' => '$2y$10$pa4OQo6fVNtaj76MxaWFD.HujDQ6SABi78uj4aSaXa4FMi1hhTvVS',
                'remember_token' => NULL,
                'updated_at' => '2022-12-27 00:00:17',
            ),
            2 => 
            array (
                'created_at' => '2022-12-27 00:00:17',
                'email' => 'caouser@etreceportal.com',
                'email_verified_at' => NULL,
                'id' => 3,
                'isActive' => 'Y',
                'name' => 'CAO User',
                'password' => '$2y$10$aWbi7UI4VTwFggk0reuewuMsrUf7rFI6iKAOWoi1l7muRcJ53ux9O',
                'remember_token' => NULL,
                'updated_at' => '2022-12-27 00:00:17',
            ),
            3 => 
            array (
                'created_at' => '2022-12-27 00:00:17',
                'email' => 'trsuser@etreceportal.com',
                'email_verified_at' => NULL,
                'id' => 4,
                'isActive' => 'Y',
                'name' => 'TRS User',
                'password' => '$2y$10$fx5escELTs3UJM8GxwcvNOpYO63j3hiUVdi..HICQ4U5nSN2VXXYS',
                'remember_token' => NULL,
                'updated_at' => '2022-12-27 00:00:17',
            ),
        ));
        
        
    }
}