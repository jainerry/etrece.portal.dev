<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class ModulesAndSubModulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $modules = [
            [
                'id' => '',
                'refID' => 'MODULE'.'-'.str_pad((0), 4, "0", STR_PAD_LEFT),
                'name' => '',
                'route'=>'',
                'description' => '',
                'parentId' => '',
                'created_at' => Carbon::now(),
            ],

        ];

        DB::table('modules')->insert($modules);

    }


}
