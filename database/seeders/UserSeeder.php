<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@etreceportal.com',
                'password' => Hash::make('superadmin@etreceportal.com'),
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'Module Admin',
                'email' => 'moduleadmin@etreceportal.com',
                'password' => Hash::make('moduleadmin@etreceportal.com'),
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'Normal User',
                'email' => 'normaluser@etreceportal.com',
                'password' => Hash::make('normaluser@etreceportal.com'),
                'created_at' => Carbon::now(),
            ]
        ]);
    }
}
